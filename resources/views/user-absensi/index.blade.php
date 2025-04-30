@extends('layouts.user')

@section('content')
    @php
        date_default_timezone_set('Asia/Jakarta'); // set zona waktu
        $now = date('H:i');
        $batas_telat = '07:30';
    @endphp

    <div class="container">
        <div class="row my-3">
            <div class="col-10">
                <h3 class="text-white">{{ Auth::user()->name }}</h3>
                <span class="text-white">{{ Auth::user()->jabatan }}</span>
            </div>
            <div class="col-2 d-flex justify-content-end">
                <div class="dropdown">
                    <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('bahan/user-avatar.png') }}" alt="" style="width: 64px; height: 64px;">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/logout">Logout</a></li>
                        <li><a class="dropdown-item" href="/profile">Profile</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card bg-light rounded-3 mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <h5 class="fw-bold">Absensi App</h5>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <span class="mt-1 text-muted">{{ date('j F Y') }}</span>
                    </div>
                </div>

                <div id="map" style="width: 100%; height: 400px;" class="mt-3"></div>
                <div class="container mt-3 text-center">
                    <h5>Ambil Foto Sebelum Absen</h5>

                    <!-- Kamera -->
                    <div class="d-flex justify-content-center">
                        <video id="video" width="320" height="240" autoplay style="border: 2px solid teal"></video>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary mt-2" id="capture">Ambil Foto</button>

                    <!-- Preview hasil foto -->
                    <div class="d-flex justify-content-center mt-2">
                        <canvas id="canvas" width="320" height="240"
                            style="display: none; border: 2px dashed gray;"></canvas>
                    </div>
                </div>

                {{-- Form Absen Masuk dan Pulang --}}
                <div class="row mt-3">
                    <div class="col-6">
                        <form id="check_in_form" action="{{ route('user.store') }}" method="post">
                            @csrf
                            <input type="hidden" name="latitude" id="check_in_latitude">
                            <input type="hidden" name="longitude" id="check_in_longitude">
                            <input type="hidden" name="type" value="check_in">
                            <button type="submit" class="btn btn-dark w-100 rounded-5" id="btn_check_in" disabled>Absen
                                Masuk</button>
                        </form>
                    </div>

                    <div class="col-6">
                        <form id="check_out_form" action="{{ route('user.store') }}" method="post">
                            @csrf
                            <input type="hidden" name="latitude" id="check_out_latitude">
                            <input type="hidden" name="longitude" id="check_out_longitude">
                            <input type="hidden" name="type" value="check_out">
                            <button type="submit" class="btn btn-dark w-100 rounded-5" id="btn_check_out">Absen
                                Pulang</button>
                        </form>
                    </div>

                    <div id="radius_warning" class="alert alert-danger mt-3 d-none" role="alert">
                        Anda berada di luar jangkauan radius absensi!
                    </div>
                    <div id="location_warning" class="alert alert-danger mt-3 d-none" role="alert">
                        Lokasi tidak dapat ditemukan. Pastikan GPS diaktifkan.
                    </div>
                </div>

                <script>
                    const video = document.getElementById('video');
                    const canvas = document.getElementById('canvas');
                    const captureButton = document.getElementById('capture');
                    const checkInButton = document.getElementById('btn_check_in');

                    // Nonaktifkan tombol absen masuk saat awal
                    checkInButton.disabled = true;

                    // Akses kamera
                    navigator.mediaDevices.getUserMedia({
                            video: true
                        })
                        .then(stream => {
                            video.srcObject = stream;
                        })
                        .catch(err => {
                            alert("Kamera tidak bisa diakses: " + err.message);
                        });

                    // Ambil gambar saat tombol diklik
                    captureButton.addEventListener('click', () => {
                        const context = canvas.getContext('2d');
                        context.drawImage(video, 0, 0, canvas.width, canvas.height);
                        canvas.style.display = 'block';
                        video.style.display = 'none'; // Sembunyikan tampilan video
                        checkInButton.disabled = false; // Aktifkan tombol absen masuk

                        // Matikan kamera setelah mengambil foto
                        const stream = video.srcObject;
                        const tracks = stream.getTracks();
                        tracks.forEach(track => track.stop());
                        video.srcObject = null;
                    });
                </script>


                <div class="mt-3">
                    <div class="row">
                        <div class="col-6">
                            <p class="fw-bold text-white">Riwayat Absensi</p>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <a href="/riwayat-absen" class="fw-bold text-white text-decoration-none">Lihat semua</a>
                        </div>
                    </div>
                </div>
            </div>
            @php
                $location = \App\Models\Location::where('is_active', true)->first();
            @endphp


            <script src="https://maps.googleapis.com/maps/api/js?key={{ env('api') }}"></script>
            <script>
                let map, marker;

                function calculateDistance(lat1, lng1, lat2, lng2) {
                    const R = 6371e3; // radius bumi dalam meter
                    const toRad = (x) => x * Math.PI / 180;
                    const dLat = toRad(lat2 - lat1);
                    const dLng = toRad(lng2 - lng1);

                    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                        Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                        Math.sin(dLng / 2) * Math.sin(dLng / 2);

                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                    return R * c; // jarak dalam meter
                }

                // Fungsi untuk menghitung jarak antara dua titik
                function initMap() {
                    map = new google.maps.Map(document.getElementById("map"), {
                        center: {
                            lat: -6.200000,
                            lng: 106.816666
                        },
                        zoom: 15,
                    });

                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            const userPos = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };
                            map.setCenter(userPos);

                            marker = new google.maps.Marker({
                                position: userPos,
                                map: map,
                                draggable: true,
                            });

                            document.getElementById('check_in_latitude').value = userPos.lat;
                            document.getElementById('check_in_longitude').value = userPos.lng;

                            marker.addListener('dragend', function() {
                                const newPos = marker.getPosition();
                                document.getElementById('check_in_latitude').value = newPos.lat();
                                document.getElementById('check_in_longitude').value = newPos.lng();
                            });

                            // Tampilkan area radius absensi
                            const centerPos = {
                                lat: {{ $location->latitude ?? -6.2 }},
                                lng: {{ $location->longitude ?? 106.816666 }},
                            };

                            const radius = {{ $location->radius ?? 100 }};

                            // Cek awal apakah user dalam radius
                            function checkDistanceAndWarn(pos) {
                                const distance = calculateDistance(pos.lat, pos.lng, centerPos.lat, centerPos.lng);
                                const warning = document.getElementById('radius_warning');
                                if (distance > radius) {
                                    warning.classList.remove('d-none');
                                } else {
                                    warning.classList.add('d-none');
                                }
                            }

                            // Jalankan pengecekan pertama
                            checkDistanceAndWarn(userPos);

                            // Jalankan saat marker digeser
                            marker.addListener('dragend', function() {
                                const newPos = marker.getPosition();
                                checkDistanceAndWarn({
                                    lat: newPos.lat(),
                                    lng: newPos.lng()
                                });
                            });

                            const radiusCircle = new google.maps.Circle({
                                strokeColor: "#FF0000",
                                strokeOpacity: 0.8,
                                strokeWeight: 2,
                                fillColor: "#FF0000",
                                fillOpacity: 0.2,
                                map,
                                center: centerPos,
                                radius: {{ $location->radius ?? 100 }},
                            });

                            // Tambahkan marker lokasi pusat absensi
                            new google.maps.Marker({
                                position: centerPos,
                                map: map,
                                icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png",
                                title: "Lokasi Absensi"
                            });

                        }, function() {
                            alert("Lokasi tidak dapat ditemukan.");
                        });
                    } else {
                        alert("Browser tidak mendukung geolokasi.");
                    }
                }


                // Jalankan peta
                window.onload = initMap;

                // Event listener untuk absen keluar
                document.getElementById('check_out_form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    const pos = marker.getPosition();
                    document.getElementById('check_out_latitude').value = pos.lat();
                    document.getElementById('check_out_longitude').value = pos.lng();
                    event.target.submit();
                });
            </script>
        @endsection

        @section('content2')
            <div class="container mb-5">
                @foreach ($attendances as $item)
                    <div class="card mt-3 bg-light rounded-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h5>{{ $item->created_at->format('j F Y') }}</h5>
                                </div>
                                <div class="col-6 text-center mt-3">
                                    <h6 class="text-muted">Jam Masuk</h6>
                                    <p class="text-primary fw-bold">
                                        {{ $item->check_in_time ? \Carbon\Carbon::parse($item->check_in_time)->format('H:i') : 'Belum Absen Masuk' }}
                                    </p>
                                </div>
                                <div class="col-6 text-center mt-3">
                                    <h6 class="text-muted">Jam Pulang</h6>
                                    <p class="text-primary fw-bold">
                                        {{ $item->check_out_time ? \Carbon\Carbon::parse($item->check_out_time)->format('H:i') : 'Belum Absen Pulang' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endsection
