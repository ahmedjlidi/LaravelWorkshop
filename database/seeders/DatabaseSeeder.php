<?php

namespace Database\Seeders;

use App\Models\{Article, Bus, Investigation, IssueReport, Recommendation, Transaction, User};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'employee@workshop.test'], [
            'name' => 'Omar · Bank employee', 'password' => 'workshop-demo', 'role' => 'employee',
        ]);
        User::updateOrCreate(['email' => 'compliance@workshop.test'], [
            'name' => 'Sara · Compliance officer', 'password' => 'workshop-demo', 'role' => 'compliance_officer',
        ]);
        $transaction = Transaction::updateOrCreate(['reference' => 'TX-1042'], [
            'customer' => 'Gulf Trading Co.', 'amount' => 12500,
        ]);
        Investigation::updateOrCreate(['transaction_id' => $transaction->id], [
            'summary' => 'CONFIDENTIAL: Repeated transfers to the same beneficiary require a source-of-funds review.',
        ]);
        Bus::updateOrCreate(['name' => 'Campus Shuttle 07'], [
            'driver' => 'Noura Ahmed', 'latitude' => 29.3117, 'longitude' => 47.4818,
        ]);
        // Re-seeding refreshes dates. Both branches use this exact same seeder.
        foreach ([
            ['Campus library extends opening hours', 'Mariam Ali', 0, 16],
            ['Student robotics team reaches the finals', 'Yousef Hassan', 1, 9],
            ['A greener campus: the next chapter', 'Dana Khaled', 1, 17],
            ['Inside the annual career fair', 'Fahad Salem', 2, 12],
            ['Scholarship applications open', 'Hessa Adel', -1, 14],
        ] as [$title, $journalist, $days, $hour]) {
            Article::updateOrCreate(['title' => $title], [
                'journalist' => $journalist, 'deadline' => today()->addDays($days)->setHour($hour),
            ]);
        }
        IssueReport::firstOrCreate(['description' => 'The streetlight outside the library has stopped working.'], [
            'location' => 'Library entrance, Block B', 'status' => 'Pending',
        ]);
        Recommendation::firstOrCreate(['interest' => 'design', 'major' => 'Architecture']);
    }
}
