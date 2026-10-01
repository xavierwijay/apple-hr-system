<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // READ (Bisa diakses oleh Admin & User)
    public function index()
    {
        $employees = Employee::latest()->get();
        return view('employees.index', compact('employees'));
    }

    // CREATE (Hanya Admin)
    public function create()
    {
        return view('employees.create');
    }

    // STORE (Hanya Admin)
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|unique:employees,employee_id',
            'nama' => 'required|string|max:255',
            'role_title' => 'required|string',
            'team_division' => 'required|string',
            'location' => 'required|string',
            'salary' => 'required|numeric',
        ]);

        Employee::create($request->all());

        return redirect()->route('employees.index')->with('success', 'Karyawan Apple berhasil ditambahkan!');
    }

    // EDIT (Hanya Admin)
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    // UPDATE (Hanya Admin)
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'employee_id' => 'required|unique:employees,employee_id,' . $employee->id,
            'nama' => 'required|string|max:255',
            'role_title' => 'required|string',
            'team_division' => 'required|string',
            'location' => 'required|string',
            'salary' => 'required|numeric',
        ]);

        $employee->update($request->all());

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diperbarui!');
    }

    // DELETE (Hanya Admin)
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil dihapus!');
    }
}