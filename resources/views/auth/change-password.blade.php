<x-layout>
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Ubah Password</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="/"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Profil User</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="card shadow-none border">
                        <div class="card-body">
                            <form class="row g-3" action="{{ route('change-password', auth()->user()->username) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="col-6">
                                    <label class="form-label">Nama User</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->username }}" disabled>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Unit</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->unit->name }}" disabled>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Role</label>
                                    <input type="text" class="form-control" disabled
                                        value="{{ auth()->user()->role == 1 ? 'Administrator' : 'Sub Administrator' }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Ubah Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                                        placeholder="Ubah Password">
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Konfirmasi Ubah Password</label>
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                        name="password_confirmation" placeholder="Konfirmasi Ubah Password">
                                    @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="text-start mt-4">
                                    <button type="submit" class="btn btn-success">Ubah Password</button>
                                    <a href="/" class="btn btn-secondary">Kembali</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 overflow-hidden">
                <div class="card-body">
                    <div class="profile-avatar text-center mt-4">
                        <img src="{{ asset('images/logoServiam.png') }}" class="rounded-circle shadow" width="120" height="120" alt="">
                    </div>
                    <div class="text-center mt-4">
                        <h4 class="mb-1">{{ auth()->user()->name }}</h4>
                        <p class="mb-4 text-secondary">
                            {{ auth()->user()->unit->name }} -- {{ auth()->user()->role == 1 ? 'Administrator' : 'Sub Administrator' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
</x-layout>
