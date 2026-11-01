<?php

namespace Tests\Feature;

use App\Models\{Investigation, User};
use Tests\WorkshopTestCase;

class BankingTest extends WorkshopTestCase
{
    public function test_compliance_officer_can_read_confidential_investigation(): void
    {
        $this->actingAs(User::where('role', 'compliance_officer')->firstOrFail())
            ->get('/challenges/banking/investigation')->assertOk()
            ->assertSee('Access granted.')->assertSee(Investigation::firstOrFail()->summary);
    }

    public function test_employee_gets_403_without_confidential_data_even_on_direct_url(): void
    {
        $this->actingAs(User::where('role', 'employee')->firstOrFail())
            ->get('/challenges/banking/investigation')->assertForbidden()
            ->assertSee('Access denied.')->assertDontSee(Investigation::firstOrFail()->summary);
    }

    public function test_guest_gets_403_without_confidential_data(): void
    {
        $this->get('/challenges/banking/investigation')->assertForbidden()
            ->assertDontSee(Investigation::firstOrFail()->summary);
    }

    public function test_public_transaction_page_does_not_expose_notes(): void
    {
        $this->get('/challenges/banking')->assertOk()->assertSee('TX-1042')
            ->assertDontSee(Investigation::firstOrFail()->summary);
    }

    public function test_identity_switch_signs_in_both_seeded_users(): void
    {
        foreach (['employee', 'compliance_officer'] as $role) {
            $this->post('/challenges/banking/identity', ['identity' => $role])->assertRedirect('/challenges/banking');
            $this->assertAuthenticatedAs(User::where('role', $role)->firstOrFail());
        }
    }

    public function test_identity_switch_rejects_unknown_role(): void
    {
        $this->post('/challenges/banking/identity', ['identity' => 'admin'])->assertSessionHasErrors('identity');
        $this->assertGuest();
    }
}
