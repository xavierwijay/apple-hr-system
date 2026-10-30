<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return EmployeeResource::collection(Employee::query()->latest()->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'string', 'max:255', 'unique:employees,employee_id'],
            'nama' => ['required', 'string', 'max:255'],
            'role_title' => ['required', 'string', 'max:255'],
            'team_division' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'salary' => ['required', 'integer', 'min:0'],
        ]);

        $employee = Employee::query()->create($validated);

        return (new EmployeeResource($employee))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Employee $employee): EmployeeResource
    {
        return new EmployeeResource($employee);
    }

    public function update(Request $request, Employee $employee): EmployeeResource
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'string', 'max:255', Rule::unique('employees', 'employee_id')->ignore($employee->id)],
            'nama' => ['required', 'string', 'max:255'],
            'role_title' => ['required', 'string', 'max:255'],
            'team_division' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'salary' => ['required', 'integer', 'min:0'],
        ]);

        $employee->update($validated);

        return new EmployeeResource($employee->refresh());
    }

    public function destroy(Employee $employee): Response
    {
        $employee->delete();

        return response()->noContent();
    }
}
