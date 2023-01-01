<x-layout>
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3 fw-semibold">Tambah Data</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item">Data Umum</li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('users') }}">Data User</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="card shadow-none bg-light border">
                                <div class="card-body">
                                    <form class="row g-3" action="{{ route('users.store') }}" method="POST">
                                        @csrf
                                        <div class="col-12 col-lg-6">
                                            <label for="name" class="form-label">Nama User</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Nama User"
                                                value="{{ old('name') }}">
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username"
                                                placeholder="Username" value="{{ old('username') }}">
                                            @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                                                placeholder="Password">
                                            @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                                name="password_confirmation" placeholder="Konfirmasi Password">
                                            @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="unit_id" class="form-label">Unit</label>
                                            <select class="form-select" name="unit_id" data-width="100%">
                                                @foreach ($units as $unit)
                                                @if(old('unit_id') == $unit->id)
                                                <option value="{{ $unit->id }}" selected>{{ $unit->name }}</option>
                                                @else
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="role" class="form-label">Unit</label>
                                            <select class="form-select" name="role" data-width="100%">
                                                <option value="1" {{ old('role')==1 ? 'selected' : '' }}>Administrator</option>
                                                <option value="2" {{ old('role')==2 ? 'selected' : '' }}>Sub Administrator</option>
                                            </select>
                                        </div>
                                        <div class="text-end mt-4">
                                            <button type="submit" class="btn btn-success fw-semibold">SIMPAN</button>
                                            <a href="{{ route('users') }}" class="btn btn-secondary fw-semibold">KEMBALI</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
