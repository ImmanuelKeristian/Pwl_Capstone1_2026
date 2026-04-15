{{-- Lokasi File: resources/views/profile/partials/update-password-form.blade.php --}}

<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="mb-3 form-password-toggle">
        <label class="form-label" for="current_password">Password Saat Ini</label>
        <div class="input-group input-group-merge">
            <input type="password" id="current_password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" name="current_password" autocomplete="current-password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
        </div>
        @error('current_password', 'updatePassword')
            <div class="text-danger mt-1 small">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3 form-password-toggle">
        <label class="form-label" for="password">Password Baru</label>
        <div class="input-group input-group-merge">
            <input type="password" id="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" name="password" autocomplete="new-password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
        </div>
        @error('password', 'updatePassword')
            <div class="text-danger mt-1 small">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4 form-password-toggle">
        <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
        <div class="input-group input-group-merge">
            <input type="password" id="password_confirmation" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" name="password_confirmation" autocomplete="new-password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
        </div>
        @error('password_confirmation', 'updatePassword')
            <div class="text-danger mt-1 small">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex align-items-center gap-3">
        <button type="submit" class="btn btn-primary">Ubah Password</button>

        @if (session('status') === 'password-updated')
            <p class="text-success mb-0 small fw-bold"
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)">
                <i class="bx bx-check-circle me-1"></i> Berhasil diubah.
            </p>
        @endif
    </div>
</form>