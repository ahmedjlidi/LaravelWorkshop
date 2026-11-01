<?php

namespace Tests\Feature;

use App\Models\IssueReport;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\WorkshopTestCase;

class CityTest extends WorkshopTestCase
{
    public function test_valid_report_is_created_and_displayed_with_pending_status(): void
    {
        $data = ['description' => 'The streetlight near gate 4 is broken.', 'location' => 'Gate 4, west entrance'];
        $before = IssueReport::count();
        $this->post('/challenges/city', $data)->assertRedirect('/challenges/city')->assertSessionHasNoErrors();
        $this->assertDatabaseCount('issue_reports', $before + 1);
        $this->assertDatabaseHas('issue_reports', [...$data, 'status' => 'Pending']);
        $this->get('/challenges/city')->assertSee($data['description'])->assertSee($data['location'])->assertSee('Pending');
    }

    public function test_status_cannot_be_supplied_by_resident(): void
    {
        $data = ['description' => 'The crossing signal is not working.', 'location' => 'East gate'];
        $this->post('/challenges/city', [...$data, 'status' => 'Resolved'])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('issue_reports', [...$data, 'status' => 'Pending']);
        $this->assertDatabaseMissing('issue_reports', ['description' => $data['description'], 'status' => 'Resolved']);
    }

    public static function invalidReports(): array
    {
        return [
            'empty description' => ['description', ''],
            'short description' => ['description', 'Broken'],
            'nine characters' => ['description', '123456789'],
            'missing location' => ['location', null],
            'blank location' => ['location', '   '],
            'description must be string' => ['description', ['not a string']],
            'location must be string' => ['location', ['not a string']],
        ];
    }

    #[DataProvider('invalidReports')]
    public function test_invalid_report_is_rejected_without_creating_record(string $field, $value): void
    {
        $data = ['description' => 'The streetlight outside is broken.', 'location' => 'North gate'];
        $data[$field] = $value;
        if ($value === null) {
            unset($data[$field]);
        }
        $before = IssueReport::count();
        $this->from('/challenges/city')->post('/challenges/city', $data)
            ->assertRedirect('/challenges/city')->assertSessionHasErrors($field);
        $this->assertDatabaseCount('issue_reports', $before);
    }

    public function test_exactly_ten_description_characters_are_accepted(): void
    {
        $this->post('/challenges/city', ['description' => '1234567890', 'location' => 'North gate'])
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('issue_reports', ['description' => '1234567890', 'status' => 'Pending']);
    }
}
