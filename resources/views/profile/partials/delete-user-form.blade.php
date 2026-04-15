{{-- Lokasi File: resources/views/profile/partials/delete-user-form.blade.php --}}

<section class="space-y-6">
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
        Hapus Akun Secara Permanen
    </button>

    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title text-danger fw-bold" id="deleteAccountModalLabel">
                            Apakah Anda yakin ingin menghapus akun?
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-muted">
                            Setelah akun Anda dihapus, semua sumber daya dan data yang terkait akan dihapus secara permanen. Silakan masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun ini secara permanen.
                        </p>

                        <div class="form-password-toggle mt-4">
                            <label class="form-label" for="delete_user_password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="delete_user_password" name="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" placeholder="Masukkan password Anda untuk mengonfirmasi" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('password', 'userDeletion')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@if ($errors->userDeletion->isNotEmpty())
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
            deleteModal.show();
        });
    </script>
    @endpush
@endif