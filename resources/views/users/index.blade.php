@push('css-plugins')
<link rel="stylesheet" href="{{ asset('plugins/datatable/css/dataTables.bootstrap5.min.css') }}">
@endpush

@push('js-plugins')
<script src="{{ asset('plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('js/datatables.js') }}"></script>
@endpush

<x-layout>
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3 fw-semibold">Data User</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Data Umum</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('users.create') }}" class="btn btn-success">Tambah Data</a>
            </div>
        </div>
    </div>
    <hr />
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped align-middle" style="width:100%">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>NAMA</th>
                            <th>USERNAME</th>
                            <th>UNIT</th>
                            <th>ROLE</th>
                            <th>LOGIN TERAKHIR</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3 cursor-pointer">
                                    <img src="{{ asset('images/logoServiam.png') }}" class="rounded-circle" width="44" height="44" alt="">
                                    <div class="">
                                        <p class="mb-0">{{ $user->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->unit->name }}</td>
                            <td>{{ $user->role === 1 ? 'Administrator' : 'Sub Administrator' }}</td>
                            <td>{{ $user->last_login }}</td>
                            <td>
                                <div class="table-actions d-flex align-items-center gap-3 fs-4">
                                    @if($user->role === 1)
                                    <a href="{{ route('change-role', [$user->username, $user->role]) }}"
                                        class="{{ $user->username === auth()->user()->username ? 'text-secondary disabled' : 'text-primary'}}"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Sub Administrator">
                                        <i class="bi bi-person-fill-down"></i>
                                    </a>
                                    @else
                                    <a href="{{ route('change-role', [$user->username, $user->role]) }}"
                                        class="{{ $user->username === auth()->user()->username ? 'text-secondary disabled' : 'text-primary'}}"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Administrator">
                                        <i class="bi bi-person-fill-up"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('users.edit', $user->username) }}"
                                        class="{{ $user->username === auth()->user()->username ? 'text-secondary disabled' : 'text-warning'}}"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ubah Data">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('users.delete', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            class="border-0 bg-transparent {{ $user->username === auth()->user()->username ? 'text-secondary' : 'text-danger'}}"
                                            {{ $user->username === auth()->user()->username ? 'disabled' : ''}} data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Hapus Data" onclick="return confirm('Yakin Ingin Menghapus Data Ini?')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
