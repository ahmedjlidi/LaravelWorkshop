<?php

namespace Tests\Feature;

use App\Models\Bus;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\WorkshopTestCase;

class BusTest extends WorkshopTestCase
{
    public static function validCoordinates(): array
    {
        return ['campus' => [29.3117, 47.4818], 'upper bounds' => [90, 180], 'lower bounds' => [-90, -180], 'zero' => [0, 0]];
    }

    #[DataProvider('validCoordinates')]
    public function test_valid_coordinates_are_saved_and_visible($latitude, $longitude): void
    {
        $this->post('/challenges/bus', compact('latitude', 'longitude'))
            ->assertRedirect('/challenges/bus')->assertSessionHasNoErrors();
        $this->assertDatabaseHas('buses', compact('latitude', 'longitude'));
        $this->get('/challenges/bus')->assertOk()->assertSee((string) $latitude)->assertSee((string) $longitude);
    }

    public static function invalidCoordinates(): array
    {
        return [
            'latitude above bounds' => ['latitude', 91],
            'latitude below bounds' => ['latitude', -91],
            'longitude above bounds' => ['longitude', 181],
            'longitude below bounds' => ['longitude', -181],
            'latitude text' => ['latitude', 'north'],
            'longitude text' => ['longitude', 'east'],
            'latitude empty' => ['latitude', ''],
            'longitude empty' => ['longitude', ''],
            'latitude array' => ['latitude', [29]],
        ];
    }

    #[DataProvider('invalidCoordinates')]
    public function test_invalid_coordinates_are_rejected_without_changing_location(string $field, $value): void
    {
        $before = Bus::firstOrFail()->only(['latitude', 'longitude']);
        $this->from('/challenges/bus')->post('/challenges/bus', array_replace($before, [$field => $value]))
            ->assertRedirect('/challenges/bus')->assertSessionHasErrors($field);
        $this->assertDatabaseHas('buses', $before);
        $this->assertEquals($before, Bus::firstOrFail()->only(['latitude', 'longitude']));
    }
}
