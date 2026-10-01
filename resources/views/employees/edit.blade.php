<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.22em] text-[#6e6e73]">Apple HR</p>
            <h2 class="mt-2 text-2xl font-semibold tracking-[-0.05em] text-[#1d1d1f]">
                {{ __('Edit Employee Data') }}
            </h2>
        </div>
    </x-slot>

    <div class="mx-auto max-w-2xl">
        <div class="apple-card p-6 sm:p-8">
            @if($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('employees.update', $employee->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-[#1d1d1f]">Employee ID</label>
                    <input type="text" name="employee_id" value="{{ $employee->employee_id }}" required class="apple-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1d1d1f]">Full Name</label>
                    <input type="text" name="nama" value="{{ $employee->nama }}" required class="apple-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1d1d1f]">Title / Role</label>
                    <select name="role_title" required class="apple-input appearance-none">
                        <option value="" disabled>Select Role</option>
                        <option value="iOS Developer" {{ $employee->role_title == 'iOS Developer' ? 'selected' : '' }}>iOS Developer</option>
                        <option value="Senior Engineer" {{ $employee->role_title == 'Senior Engineer' ? 'selected' : '' }}>Senior Engineer</option>
                        <option value="Product Manager" {{ $employee->role_title == 'Product Manager' ? 'selected' : '' }}>Product Manager</option>
                        <option value="HR Manager" {{ $employee->role_title == 'HR Manager' ? 'selected' : '' }}>HR Manager</option>
                        <option value="Data Analyst" {{ $employee->role_title == 'Data Analyst' ? 'selected' : '' }}>Data Analyst</option>
                        <option value="QA Engineer" {{ $employee->role_title == 'QA Engineer' ? 'selected' : '' }}>QA Engineer</option>
                        <option value="Design Lead" {{ $employee->role_title == 'Design Lead' ? 'selected' : '' }}>Design Lead</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1d1d1f]">Division</label>
                    <select name="team_division" required class="apple-input appearance-none">
                        <option value="" disabled>Select Division</option>
                        <option value="Software Engineering" {{ $employee->team_division == 'Software Engineering' ? 'selected' : '' }}>Software Engineering</option>
                        <option value="Hardware Engineering" {{ $employee->team_division == 'Hardware Engineering' ? 'selected' : '' }}>Hardware Engineering</option>
                        <option value="Design" {{ $employee->team_division == 'Design' ? 'selected' : '' }}>Design</option>
                        <option value="Operations" {{ $employee->team_division == 'Operations' ? 'selected' : '' }}>Operations</option>
                        <option value="People & Culture" {{ $employee->team_division == 'People & Culture' ? 'selected' : '' }}>People & Culture</option>
                        <option value="Finance" {{ $employee->team_division == 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="Security" {{ $employee->team_division == 'Security' ? 'selected' : '' }}>Security</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1d1d1f]">Work Location</label>
                    <select name="location" required class="apple-input appearance-none">
                        <option value="" disabled>Select Location</option>
                        <option value="Apple Park, Cupertino" {{ $employee->location == 'Apple Park, Cupertino' ? 'selected' : '' }}>Apple Park, Cupertino</option>
                        <option value="Apple Infinite Loop, Cupertino" {{ $employee->location == 'Apple Infinite Loop, Cupertino' ? 'selected' : '' }}>Apple Infinite Loop, Cupertino</option>
                        <option value="One Apple Park Way, Cupertino" {{ $employee->location == 'One Apple Park Way, Cupertino' ? 'selected' : '' }}>One Apple Park Way, Cupertino</option>
                        <option value="Apple Singapore" {{ $employee->location == 'Apple Singapore' ? 'selected' : '' }}>Apple Singapore</option>
                        <option value="Apple Jakarta" {{ $employee->location == 'Apple Jakarta' ? 'selected' : '' }}>Apple Jakarta</option>
                        <option value="Apple Seoul" {{ $employee->location == 'Apple Seoul' ? 'selected' : '' }}>Apple Seoul</option>
                        <option value="Apple Tokyo" {{ $employee->location == 'Apple Tokyo' ? 'selected' : '' }}>Apple Tokyo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1d1d1f]">Salary (USD / Year)</label>
                    <input type="number" name="salary" value="{{ $employee->salary }}" required class="apple-input">
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-[#ececec] pt-6">
                    <a href="{{ route('employees.index') }}" class="apple-button-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="apple-button">
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>