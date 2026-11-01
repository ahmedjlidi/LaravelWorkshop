<?php

namespace Tests\Feature;

use App\Models\{Article, Bus, Investigation, IssueReport, Recommendation, Transaction, User};
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\WorkshopTestCase;

class WorkshopPagesTest extends WorkshopTestCase
{
    public static function pages(): array
    {
        return [['/', 'Choose your challenge'], ['/challenges/banking', 'AppServiceProvider.php'],
            ['/challenges/bus', 'BusController.php'], ['/challenges/newsroom', 'NewsroomController.php'],
            ['/challenges/city', 'CityController.php'], ['/challenges/ai-advisor', 'AdvisorController.php']];
    }

    #[DataProvider('pages')]
    public function test_workshop_pages_render(string $path, string $expected): void
    {
        $this->get($path)->assertOk()->assertSee($expected);
    }

    public function test_seeding_twice_does_not_duplicate_sample_records(): void
    {
        $this->seed();
        $this->assertSame(2, User::count());
        $this->assertSame(1, Transaction::count());
        $this->assertSame(1, Investigation::count());
        $this->assertSame(1, Bus::count());
        $this->assertSame(5, Article::count());
        $this->assertSame(1, IssueReport::count());
        $this->assertSame(1, Recommendation::count());
    }
}
