<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-[10px] font-medium uppercase tracking-[0.22em] text-[#6e6e73]">Apple Directory</p>
                <h2 class="mt-2 text-2xl font-semibold tracking-[-0.05em] text-[#1d1d1f]">Directory Services</h2>
            </div>
        </div>
    </x-slot>

    <div class="apple-card p-6 sm:p-8">
        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h3 class="apple-section-title">Employee Information System</h3>
                <p class="apple-muted mt-2">Team and employee directory for the Apple Ecosystem.</p>
            </div>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('employees.create') }}" class="apple-button">
                    + Add Employee
                </a>
            @endif
        </div>

        <div class="overflow-hidden rounded-[24px] border border-[#e5e5e5]">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm">
                    <thead class="bg-[#f5f5f7]">
                        <tr class="text-[#1d1d1f]">
                            <th class="px-4 py-3 font-semibold">Employee ID</th>
                            <th class="px-4 py-3 font-semibold">Name</th>
                            <th class="px-4 py-3 font-semibold">Title / Role</th>
                            <th class="px-4 py-3 font-semibold">Division</th>
                            <th class="px-4 py-3 font-semibold">Location</th>
                            <th class="px-4 py-3 font-semibold">Salary</th>
                            @if(auth()->user()->role === 'admin')
                                <th class="px-4 py-3 text-right font-semibold">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#ececec] bg-white text-[#1d1d1f]">
                        @forelse($employees as $emp)
                            <tr class="transition hover:bg-[#f9f9f9]">
                                <td class="px-4 py-4 font-mono text-[11px] font-bold tracking-[0.12em] text-[#1d1d1f]">{{ $emp->employee_id }}</td>
                                <td class="px-4 py-4 font-semibold text-[#1d1d1f]">{{ $emp->nama }}</td>
                                <td class="px-4 py-4 text-[#3e3e3f]">{{ $emp->role_title }}</td>
                                <td class="px-4 py-4">
                                    <span class="apple-pill">
                                        {{ $emp->team_division }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-[#3e3e3f]">{{ $emp->location }}</td>
                                <td class="px-4 py-4 font-semibold text-[#1d1d1f]">${{ number_format($emp->salary, 0) }}/thn</td>

                                @if(auth()->user()->role === 'admin')
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('employees.edit', $emp->id) }}" class="text-sm font-semibold text-[#1d1d1f] underline decoration-[#7d7d7d] underline-offset-4 transition hover:text-[#4c4c4c]">Edit</a>
                                            <form action="{{ route('employees.destroy', $emp->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-semibold text-[#b42318] underline decoration-[#d97706] underline-offset-4 transition hover:text-[#7f1d1d]">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role === 'admin' ? 7 : 6 }}" class="px-4 py-10 text-center text-sm text-[#6e6e73]">
                                    No employee data available yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>