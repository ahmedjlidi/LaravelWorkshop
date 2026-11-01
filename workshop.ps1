# Windows PowerShell 5.1+ / PowerShell 7. No host PHP or Composer is used.
param(
    [ValidateSet('start', 'stop', 'status', 'logs', 'test', 'seed', 'reset')]
    [string]$Action = 'start',
    [string]$Filter = ''
)
$ErrorActionPreference = 'Stop'
Set-Location -LiteralPath $PSScriptRoot

function Invoke-WorkshopCompose {
    param([string[]]$Arguments)
    & docker compose @Arguments
    if ($LASTEXITCODE -ne 0) {
        throw 'Docker Compose failed. Read the error above and DOCKER_SETUP.md; run .\workshop.ps1 logs for startup details.'
    }
}

try {
    if ($Filter -and $Action -ne 'test') {
        throw 'Only test accepts a second argument (the test class).'
    }
    if (-not (Get-Command docker -CommandType Application -ErrorAction SilentlyContinue)) {
        throw 'Docker is missing. Install Docker Desktop using DOCKER_SETUP.md: https://docs.docker.com/desktop/setup/install/windows-install/'
    }
    & docker compose version *> $null
    if ($LASTEXITCODE -ne 0) {
        throw 'Docker Compose v2 is missing. Install/update Docker Desktop; see DOCKER_SETUP.md.'
    }
    & docker info *> $null
    if ($LASTEXITCODE -ne 0 -and $Action -eq 'start') {
        $desktopCandidates = @(
            (Join-Path $env:ProgramFiles 'Docker\Docker\Docker Desktop.exe'),
            (Join-Path $env:LOCALAPPDATA 'Programs\DockerDesktop\Docker Desktop.exe')
        )
        $desktopPath = $desktopCandidates | Where-Object { Test-Path -LiteralPath $_ } | Select-Object -First 1
        if ($desktopPath) {
            Write-Host 'Starting Docker Desktop. Complete any first-run prompts in its window.'
            Start-Process -FilePath $desktopPath
            for ($attempt = 0; $attempt -lt 45; $attempt++) {
                Start-Sleep -Seconds 2
                & docker info *> $null
                if ($LASTEXITCODE -eq 0) { break }
            }
        }
    }
    & docker info *> $null
    if ($LASTEXITCODE -ne 0) {
        throw 'Docker is installed but its engine is unavailable. Start Docker Desktop and finish WSL 2 setup/any required restart; see DOCKER_SETUP.md.'
    }
    $containerOS = & docker info --format '{{.OSType}}'
    if ($LASTEXITCODE -ne 0 -or $containerOS.Trim() -ne 'linux') {
        throw 'Switch Docker Desktop to Linux containers, then retry.'
    }
    switch ($Action) {
        'start' {
            $portText = if ($env:WORKSHOP_PORT) { $env:WORKSHOP_PORT } else { '8080' }
            $portNumber = 0
            if (-not [int]::TryParse($portText, [ref]$portNumber) -or $portNumber -lt 1 -or $portNumber -gt 65535) {
                throw 'WORKSHOP_PORT must be an integer from 1 to 65535.'
            }
            Invoke-WorkshopCompose -Arguments @('up', '--build', '--detach', '--wait', '--wait-timeout', '180')
            Write-Host "Workshop ready: http://127.0.0.1:$portText/"
            Write-Host 'Edit your TODO files normally; PHP changes are visible on the next request.'
            Write-Host 'The server keeps running after this terminal closes. Stop it with: .\workshop.ps1 stop'
        }
        'stop' { Invoke-WorkshopCompose -Arguments @('down') }
        'status' { Invoke-WorkshopCompose -Arguments @('ps') }
        'logs' { Invoke-WorkshopCompose -Arguments @('logs', '--tail', '100', 'app') }
        'test' {
            $testArguments = @('exec', '-T', 'app', 'workshop-artisan', 'test')
            if ($Filter) { $testArguments += "--filter=$Filter" }
            & docker compose @testArguments
            exit $LASTEXITCODE
        }
        'seed' { Invoke-WorkshopCompose -Arguments @('exec', '-T', 'app', 'workshop-artisan', 'db:seed', '--force', '--no-interaction') }
        'reset' {
            $confirmation = Read-Host 'This erases Docker workshop data and restores samples. Type RESET'
            if ($confirmation -ne 'RESET') { Write-Host 'Reset cancelled.'; exit 0 }
            Invoke-WorkshopCompose -Arguments @('exec', '-T', 'app', 'workshop-artisan', 'migrate:fresh', '--seed', '--force', '--no-interaction')
        }
    }
}
catch {
    Write-Host $_.Exception.Message -ForegroundColor Red
    exit 1
}
