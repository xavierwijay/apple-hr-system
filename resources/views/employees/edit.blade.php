<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.22em] text-[#6e6e73]">Apple HR</p>
            <h2 class="mt-2 text-2xl font-semibold tracking-[-0.05em] text-[#1d1d1f]">
                {{ __('Edit Data Karyawan') }}
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
                    <label class="block text-sm font-medium text-[#1d1d1f]">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ $employee->nama }}" required class="apple-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1d1d1f]">Jabatan / Role</label>
                    <input type="text" name="role_title" value="{{ $employee->role_title }}" required class="apple-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1d1d1f]">Divisi</label>
                    <input type="text" name="team_division" value="{{ $employee->team_division }}" required class="apple-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1d1d1f]">Lokasi Kerja</label>
                    <input type="text" name="location" value="{{ $employee->location }}" required class="apple-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1d1d1f]">Gaji (USD / Tahun)</label>
                    <input type="number" name="salary" value="{{ $employee->salary }}" required class="apple-input">
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-[#ececec] pt-6">
                    <a href="{{ route('employees.index') }}" class="apple-button-secondary">
                        Batal
                    </a>
                    <button type="submit" class="apple-button">
                        Perbarui Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>