<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.22em] text-[#6e6e73]">Apple HR</p>
            <h2 class="mt-2 text-2xl font-semibold tracking-[-0.05em] text-[#1d1d1f]">
                {{ __('Add New Employee') }}
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

            <form action="{{ route('employees.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-[#1d1d1f]">Employee ID</label>
                    <input type="text" name="employee_id" placeholder="AAPL-103" required class="apple-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1d1d1f]">Full Name</label>
                    <input type="text" name="nama" required class="apple-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1d1d1f]">Title / Role</label>
                    <select name="role_title" required class="apple-input appearance-none">
                        <option value="" disabled selected>Select Role</option>
                        <option value="iOS Developer">iOS Developer</option>
                        <option value="Senior Engineer">Senior Engineer</option>
                        <option value="Product Manager">Product Manager</option>
                        <option value="HR Manager">HR Manager</option>
                        <option value="Data Analyst">Data Analyst</option>
                        <option value="QA Engineer">QA Engineer</option>
                        <option value="Design Lead">Design Lead</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1d1d1f]">Division</label>
                    <select name="team_division" required class="apple-input appearance-none">
                        <option value="" disabled selected>Select Division</option>
                        <option value="Software Engineering">Software Engineering</option>
                        <option value="Hardware Engineering">Hardware Engineering</option>
                        <option value="Design">Design</option>
                        <option value="Operations">Operations</option>
                        <option value="People & Culture">People & Culture</option>
                        <option value="Finance">Finance</option>
                        <option value="Security">Security</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1d1d1f]">Work Location</label>
                    <select name="location" required class="apple-input appearance-none">
                        <option value="" disabled selected>Select Location</option>
                        <option value="Apple Park, Cupertino">Apple Park, Cupertino</option>
                        <option value="Apple Infinite Loop, Cupertino">Apple Infinite Loop, Cupertino</option>
                        <option value="One Apple Park Way, Cupertino">One Apple Park Way, Cupertino</option>
                        <option value="Apple Singapore">Apple Singapore</option>
                        <option value="Apple Jakarta">Apple Jakarta</option>
                        <option value="Apple Seoul">Apple Seoul</option>
                        <option value="Apple Tokyo">Apple Tokyo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1d1d1f]">Salary (USD / Year)</label>
                    <input type="number" name="salary" placeholder="120000" required class="apple-input">
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-[#ececec] pt-6">
                    <a href="{{ route('employees.index') }}" class="apple-button-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="apple-button">
                        Save Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>