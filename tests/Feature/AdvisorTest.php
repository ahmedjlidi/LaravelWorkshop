<?php

namespace Tests\Feature;

use App\Models\Recommendation;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\WorkshopTestCase;

class AdvisorTest extends WorkshopTestCase
{
    public static function interests(): array
    {
        return [['programming', 'Computer Science'], ['biology', 'Medicine'], ['design', 'Architecture']];
    }

    #[DataProvider('interests')]
    public function test_interest_is_mapped_saved_and_displayed(string $interest, string $major): void
    {
        $before = Recommendation::count();
        $this->post('/challenges/ai-advisor', compact('interest'))->assertRedirect('/challenges/ai-advisor')->assertSessionHasNoErrors();
        $this->assertDatabaseCount('recommendations', $before + 1);
        $this->assertSame($major, Recommendation::latest('id')->firstOrFail()->major);
        $this->assertDatabaseHas('recommendations', compact('interest', 'major'));
        $this->get('/challenges/ai-advisor')->assertSee($major)->assertSee(ucfirst($interest));
    }

    public function test_unknown_interest_is_rejected_without_saving(): void
    {
        $before = Recommendation::count();
        $this->post('/challenges/ai-advisor', ['interest' => 'unknown'])->assertSessionHasErrors('interest');
        $this->assertDatabaseCount('recommendations', $before);
    }

    public function test_missing_interest_is_rejected_without_saving(): void
    {
        $before = Recommendation::count();
        $this->post('/challenges/ai-advisor', [])->assertSessionHasErrors('interest');
        $this->assertDatabaseCount('recommendations', $before);
    }
}
