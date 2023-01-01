<header class="top-header">
    <nav class="navbar navbar-expand gap-3 align-items-center">
        <div class="mobile-toggle-icon fs-3">
            <i class="bi bi-list"></i>
        </div>
        <div class="top-navbar-right ms-auto">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item dropdown dropdown-user-setting">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                        <div class="user-setting d-flex align-items-center">
                            <img src="{{ asset('images/logoServiam.png') }}" class="user-img" alt="">
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('change-password-view', auth()->user()->username) }}">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('images/logoServiam.png') }}" alt="" class="rounded-circle" width="54" height="54">
                                    <div class="ms-3">
                                        <h6 class="mb-0 dropdown-user-name">{{ auth()->user()->name }}</h6>
                                        <small class="mb-0 dropdown-user-designation text-secondary">
                                            {{ auth()->user()->role == 1 ? 'Administrator' : 'Sub Administrator' }} - {{ (auth()->user()->unit->code) }}
                                        </small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}">
                                <div class="d-flex align-items-center">
                                    <div class=""><i class="bi bi-lock-fill"></i></div>
                                    <div class="ms-3"><span>Logout</span></div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
