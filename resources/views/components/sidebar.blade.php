<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('images/logoServiam.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">SISARPRAS</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class="bi bi-list"></i></div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{ route('dashboard') }}">
                <div class="parent-icon"><i class="bi bi-house-fill"></i></div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>

        @if(auth()->user()->unit_id == 1)
        <li class="menu-label">Yayasan</li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bi bi-pen-fill"></i></div>
                <div class="menu-title">Habis Pakai</div>
            </a>
            <ul>
                <li>
                    <a href="{{ route('consumable-items', 'yayasan') }}">
                        <i class="bi bi-circle-fill"></i>Data Barang
                    </a>
                </li>
                <li>
                    <a href="{{ route('consumable-items.categories', 'yayasan') }}">
                        <i class="bi bi-circle-fill"></i>Data Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('consumable-items.sub-categories', 'yayasan') }}">
                        <i class="bi bi-circle-fill"></i>Data Sub Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('consumable-items.pickup-items', 'yayasan') }}">
                        <i class="bi bi-circle-fill"></i>Data Pengambilan
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bi bi-pc-display"></i></div>
                <div class="menu-title">Tidak Habis Pakai</div>
            </a>
            <ul>
                <li>
                    <a href="{{ route('non-consumable-items', 'yayasan') }}">
                        <i class="bi bi-circle-fill"></i>Data Barang
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.categories', 'yayasan') }}">
                        <i class="bi bi-circle-fill"></i>Data Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.sub-categories', 'yayasan') }}">
                        <i class="bi bi-circle-fill"></i>Data Sub Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.conditions', 'yayasan') }}">
                        <i class="bi bi-circle-fill"></i>Data Kondisi
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.loan-items', 'yayasan') }}">
                        <i class="bi bi-circle-fill"></i>Data Peminjaman
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.placement-items', 'yayasan') }}">
                        <i class="bi bi-circle-fill"></i>Data Penempatan
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ route('consumers', 'yayasan') }}">
                <div class="parent-icon"><i class="bi bi-person-square"></i></div>
                <div class="menu-title">Data Pengguna</div>
            </a>
        </li>
        <li>
            <a href="{{ route('rooms', 'yayasan') }}">
                <div class="parent-icon"><i class="bi bi-bank2"></i></div>
                <div class="menu-title">Data Ruangan</div>
            </a>
        </li>
        @endif

        @if((auth()->user()->unit_id == 1 && auth()->user()->role == 1) || auth()->user()->unit_id == 2)
        <li class="menu-label">TB-TK</li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bi bi-pen-fill"></i></div>
                <div class="menu-title">Habis Pakai</div>
            </a>
            <ul>
                <li>
                    <a href="{{ route('consumable-items', 'tbtk') }}">
                        <i class="bi bi-circle-fill"></i>Data Barang
                    </a>
                </li>
                <li>
                    <a href="{{ route('consumable-items.categories', 'tbtk') }}">
                        <i class="bi bi-circle-fill"></i>Data Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('consumable-items.sub-categories', 'tbtj') }}">
                        <i class="bi bi-circle-fill"></i>Data Sub Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('consumable-items.pickup-items', 'tbtk') }}">
                        <i class="bi bi-circle-fill"></i>Data Pengambilan
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bi bi-pc-display"></i></div>
                <div class="menu-title">Tidak Habis Pakai</div>
            </a>
            <ul>
                <li>
                    <a href="{{ route('non-consumable-items', 'tbtk') }}">
                        <i class="bi bi-circle-fill"></i>Data Barang
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.categories', 'tbtk') }}">
                        <i class="bi bi-circle-fill"></i>Data Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.sub-categories', 'tbtk') }}">
                        <i class="bi bi-circle-fill"></i>Data Sub Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.conditions', 'tbtk') }}">
                        <i class="bi bi-circle-fill"></i>Data Kondisi
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.loan-items', 'tbtk') }}">
                        <i class="bi bi-circle-fill"></i>Data Peminjaman
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.placement-items', 'tbtk') }}">
                        <i class="bi bi-circle-fill"></i>Data Penempatan
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ route('consumers', 'tbtk') }}">
                <div class="parent-icon"><i class="bi bi-person-square"></i></div>
                <div class="menu-title">Data Pengguna</div>
            </a>
        </li>
        <li>
            <a href="{{ route('rooms', 'tbtk') }}">
                <div class="parent-icon"><i class="bi bi-bank2"></i></div>
                <div class="menu-title">Data Ruangan</div>
            </a>
        </li>
        @endif

        @if((auth()->user()->unit_id == 1 && auth()->user()->role == 1) || auth()->user()->unit_id == 3)
        <li class="menu-label">SD</li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bi bi-pen-fill"></i></div>
                <div class="menu-title">Habis Pakai</div>
            </a>
            <ul>
                <li>
                    <a href="{{ route('consumable-items', 'sd') }}">
                        <i class="bi bi-circle-fill"></i>Data Barang
                    </a>
                </li>
                <li>
                    <a href="{{ route('consumable-items.categories', 'sd') }}">
                        <i class="bi bi-circle-fill"></i>Data Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('consumable-items.sub-categories', 'sd') }}">
                        <i class="bi bi-circle-fill"></i>Data Sub Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('consumable-items.pickup-items', 'sd') }}">
                        <i class="bi bi-circle-fill"></i>Data Pengambilan
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bi bi-pc-display"></i></div>
                <div class="menu-title">Tidak Habis Pakai</div>
            </a>
            <ul>
                <li>
                    <a href="{{ route('non-consumable-items', 'sd') }}">
                        <i class="bi bi-circle-fill"></i>Data Barang
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.categories', 'sd') }}">
                        <i class="bi bi-circle-fill"></i>Data Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.sub-categories', 'sd') }}">
                        <i class="bi bi-circle-fill"></i>Data Sub Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.conditions', 'sd') }}">
                        <i class="bi bi-circle-fill"></i>Data Kondisi
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.loan-items', 'sd') }}">
                        <i class="bi bi-circle-fill"></i>Data Peminjaman
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.placement-items', 'sd') }}">
                        <i class="bi bi-circle-fill"></i>Data Penempatan
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ route('consumers', 'sd') }}">
                <div class="parent-icon"><i class="bi bi-person-square"></i></div>
                <div class="menu-title">Data Pengguna</div>
            </a>
        </li>
        <li>
            <a href="{{ route('rooms', 'sd') }}">
                <div class="parent-icon"><i class="bi bi-bank2"></i></div>
                <div class="menu-title">Data Ruangan</div>
            </a>
        </li>
        @endif

        @if((auth()->user()->unit_id == 1 && auth()->user()->role == 1) || auth()->user()->unit_id == 4)
        <li class="menu-label">SMP</li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bi bi-pen-fill"></i></div>
                <div class="menu-title">Habis Pakai</div>
            </a>
            <ul>
                <li>
                    <a href="{{ route('consumable-items', 'smp') }}">
                        <i class="bi bi-circle-fill"></i>Data Barang
                    </a>
                </li>
                <li>
                    <a href="{{ route('consumable-items.categories', 'smp') }}">
                        <i class="bi bi-circle-fill"></i>Data Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('consumable-items.sub-categories', 'smp') }}">
                        <i class="bi bi-circle-fill"></i>Data Sub Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('consumable-items.pickup-items', 'smp') }}">
                        <i class="bi bi-circle-fill"></i>Data Pengambilan
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bi bi-pc-display"></i></div>
                <div class="menu-title">Tidak Habis Pakai</div>
            </a>
            <ul>
                <li>
                    <a href="{{ route('non-consumable-items', 'smp') }}">
                        <i class="bi bi-circle-fill"></i>Data Barang
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.categories', 'smp') }}">
                        <i class="bi bi-circle-fill"></i>Data Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.sub-categories', 'smp') }}">
                        <i class="bi bi-circle-fill"></i>Data Sub Kategori
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.conditions', 'smp') }}">
                        <i class="bi bi-circle-fill"></i>Data Kondisi
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.loan-items', 'smp') }}">
                        <i class="bi bi-circle-fill"></i>Data Peminjaman
                    </a>
                </li>
                <li>
                    <a href="{{ route('non-consumable-items.placement-items', 'smp') }}">
                        <i class="bi bi-circle-fill"></i>Data Penempatan
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ route('consumers', 'smp') }}">
                <div class="parent-icon"><i class="bi bi-person-square"></i></div>
                <div class="menu-title">Data Pengguna</div>
            </a>
        </li>
        <li>
            <a href="{{ route('rooms', 'smp') }}">
                <div class="parent-icon"><i class="bi bi-bank2"></i></div>
                <div class="menu-title">Data Ruangan</div>
            </a>
        </li>
        @endif

        <li class="menu-label">Data Umum</li>
        <li>
            <a href="{{ route('brands') }}">
                <div class="parent-icon"><i class="bi bi-medium"></i></div>
                <div class="menu-title">Data Merk</div>
            </a>
        </li>
        <li>
            <a href="{{ route('positions') }}">
                <div class="parent-icon"><i class="bi bi-person-vcard-fill"></i></div>
                <div class="menu-title">Data Posisi Pengguna</div>
            </a>
        </li>
        <li>
            <a href="{{ route('funds') }}">
                <div class="parent-icon"><i class="bi bi-wallet-fill"></i></div>
                <div class="menu-title">Data Sumber Dana</div>
            </a>
        </li>
        <li>
            <a href="{{ route('shops') }}">
                <div class="parent-icon"><i class="bi bi-archive-fill"></i></div>
                <div class="menu-title">Data Vendor</div>
            </a>
        </li>
        @if((auth()->user()->unit_id == 1 && auth()->user()->role == 1))
        <li>
            <a href="{{ route('users') }}">
                <div class="parent-icon"><i class="bi bi-people-fill"></i></div>
                <div class="menu-title">Data User</div>
            </a>
        </li>
        @endif
    </ul>
</aside>
