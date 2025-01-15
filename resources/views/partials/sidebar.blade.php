<div class="sidebar border-end col-md-3 col-lg-2 p-0" style="background-color: #e3fcec;">
    <div class="offcanvas-md offcanvas-end" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel"
        style="background-color: #e3fcec;">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title text-success fw-bold" id="sidebarMenuLabel">Absensi App</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 text-dark fw-semibold" aria-current="page" href="/dashboard">
                        <svg class="bi text-success" width="20" height="20">
                            <use xlink:href="#house-fill" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 text-dark fw-semibold"
                        href="{{ route('manage-user.index') }}">
                        <svg class="bi text-success" width="20" height="20">
                            <use xlink:href="#users" />
                        </svg>
                        Data Pengguna
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 text-dark fw-semibold"
                        href="{{ route('manage-location.index') }}">
                        <svg class="bi text-success" width="20" height="20">
                            <use xlink:href="#location" />
                        </svg>
                        Kelola Lokasi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 text-dark fw-semibold" href="/attendance-history">
                        <svg class="bi text-success" width="20" height="20">
                            <use xlink:href="#history" />
                        </svg>
                        Riwayat Absensi
                    </a>
                </li>
            </ul>

            <hr class="my-3 border-success">

            <ul class="nav flex-column mb-auto">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 text-danger fw-semibold" href="/logout">
                        <svg class="bi text-danger" width="20" height="20">
                            <use xlink:href="#door-closed" />
                        </svg>
                        Sign out
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
