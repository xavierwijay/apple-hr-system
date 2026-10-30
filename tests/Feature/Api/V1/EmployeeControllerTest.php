<?php

namespace Tests\Feature\Api\V1;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_401_for_employee_list_without_a_token(): void
    {
        $this->getJson('/api/v1/employees')->assertUnauthorized();
    }

    public function test_user_can_read_employee_records(): void
    {
        $employee = Employee::query()->create($this->employeeData());
        $token = $this->loginAs(User::factory()->create(['role' => 'user']));

        $this->withToken($token)
            ->getJson('/api/v1/employees')
            ->assertOk()
            ->assertJsonPath('data.0.employee_id', $employee->employee_id);
    }

    public function test_user_receives_403_for_create_update_and_delete_requests(): void
    {
        $employee = Employee::query()->create($this->employeeData());
        $token = $this->loginAs(User::factory()->create(['role' => 'user']));

        $this->withToken($token)
            ->postJson('/api/v1/employees', $this->employeeData(['employee_id' => 'AAPL-104']))
            ->assertForbidden();

        $this->withToken($token)
            ->putJson('/api/v1/employees/'.$employee->id, $this->employeeData(['nama' => 'Changed Name']))
            ->assertForbidden();

        $this->withToken($token)
            ->deleteJson('/api/v1/employees/'.$employee->id)
            ->assertForbidden();

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'nama' => 'Jony Ive',
        ]);
    }

    public function test_admin_can_create_update_and_delete_employee_records(): void
    {
        $token = $this->loginAs(User::factory()->create(['role' => 'admin']));

        $createResponse = $this->withToken($token)
            ->postJson('/api/v1/employees', $this->employeeData());

        $createResponse->assertCreated()
            ->assertJsonPath('data.employee_id', 'AAPL-103');

        $employee = Employee::query()->where('employee_id', 'AAPL-103')->firstOrFail();
        $this->assertModelExists($employee);

        $this->withToken($token)
            ->putJson('/api/v1/employees/'.$employee->id, $this->employeeData(['nama' => 'Updated Employee']))
            ->assertOk()
            ->assertJsonPath('data.nama', 'Updated Employee');

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'nama' => 'Updated Employee',
        ]);

        $this->withToken($token)
            ->deleteJson('/api/v1/employees/'.$employee->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }

    public function test_admin_receives_validation_errors_for_incomplete_employee_data(): void
    {
        $token = $this->loginAs(User::factory()->create(['role' => 'admin']));

        $this->withToken($token)
            ->postJson('/api/v1/employees', ['employee_id' => 'AAPL-103'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['nama', 'role_title', 'team_division', 'location', 'salary']);

        $this->assertDatabaseCount('employees', 0);
    }

    private function loginAs(User $user): string
    {
        $token = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertOk()->json('token');

        app('tymon.jwt')->unsetToken();
        Auth::forgetGuards();

        return $token;
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function employeeData(array $overrides = []): array
    {
        return array_merge([
            'employee_id' => 'AAPL-103',
            'nama' => 'Jony Ive',
            'role_title' => 'Chief Industrial Designer',
            'team_division' => 'Hardware Design',
            'location' => 'Apple Park, Cupertino',
            'salary' => 150000,
        ], $overrides);
    }
}
