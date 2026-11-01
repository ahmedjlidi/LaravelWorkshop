<?php

namespace Tests\Feature;

use App\Models\Article;
use Tests\WorkshopTestCase;

class NewsroomTest extends WorkshopTestCase
{
    public function test_only_tomorrows_deadlines_are_displayed_including_different_times(): void
    {
        $response = $this->get('/challenges/newsroom')->assertOk();
        $response->assertViewHas('articles', function ($articles) {
            return $articles->count() === 2
                && $articles->pluck('title')->all() === [
                    'Student robotics team reaches the finals',
                    'A greener campus: the next chapter',
                ];
        });
        $response->assertSee('Yousef Hassan')->assertSee('Dana Khaled')->assertSee('Jan 16, 2030')
            ->assertDontSee('Campus library extends opening hours')->assertDontSee('Inside the annual career fair')
            ->assertDontSee('Scholarship applications open');
    }

    public function test_no_articles_are_returned_when_nothing_is_due_tomorrow(): void
    {
        Article::whereIn('title', ['Student robotics team reaches the finals', 'A greener campus: the next chapter'])->delete();
        $this->get('/challenges/newsroom')->assertOk()->assertViewHas('articles', fn ($articles) => $articles->isEmpty());
    }
}
