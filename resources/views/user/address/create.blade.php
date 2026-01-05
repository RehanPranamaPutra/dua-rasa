    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tambah Alamat</title>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f4f7f6;
            }

            .container {
                width: 50%;
                margin: 50px auto;
                padding: 30px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .form-group {
                margin-bottom: 15px;
            }

            label {
                display: block;
                font-weight: bold;
                margin-bottom: 5px;
            }

            input,
            select,
            textarea {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 4px;
                box-sizing: border-box;
            }

            button {
                background-color: #b52d39;
                color: white;
                padding: 12px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                width: 100%;
                font-size: 16px;
            }

            button:hover {
                background-color: #dc3545;
            }

            .alert {
                padding: 10px;
                margin-bottom: 15px;
                border-radius: 5px;
            }

            .alert-danger {
                background-color: #f8d7da;
                color: #842029;
            }
        </style>
    </head>

    <body>

        <div class="container">
            <h2>Tambah Alamat Baru 🗺️</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('customer.address.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Nama Penerima:</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}"
                        placeholder="Contoh: Budi Santoso">
                </div>

                <div class="form-group">
                    <label>Nomor Telepon:</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp') }}" placeholder="0812xxxx">
                </div>

                <hr>

                <!-- Bagian Provinsi -->
                <div class="form-group">
                    <label>Provinsi:</label>
                    <!-- Tambahkan name="province_id" -->
                    <select id="province-select" name="province_id">
                        <option value="">Pilih Provinsi</option>
                        @foreach ($provinces as $province)
                            <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                        @endforeach
                    </select>
                    <!-- Ubah name menjadi "province_name" sesuai controller -->
                    <input type="hidden" id="province-name" name="province_name">
                </div>

                <!-- Bagian Kota -->
                <div class="form-group">
                    <label>Kabupaten/Kota:</label>
                    <!-- Tambahkan name="city_id" -->
                    <select id="city-select" name="city_id" disabled>
                        <option value="">Pilih Kabupaten/Kota</option>
                    </select>
                    <!-- Ubah name menjadi "city_name" sesuai controller -->
                    <input type="hidden" id="city-name" name="city_name">
                </div>

                <!-- Tambahkan name untuk Kecamatan dan Desa jika ingin disimpan juga -->
                <div class="form-group">
                    <label>Kecamatan:</label>
                    <select id="district-select" name="subdistrict_id" disabled>
                        <option value="">Pilih Kecamatan</option>
                    </select>
                    <input type="hidden" id="district-name" name="subdistrict">
                </div>

                <div class="form-group">
                    <label>Kelurahan/Desa:</label>
                    <select id="village-select" name="village" disabled>
                        <option value="">Pilih Kelurahan/Desa</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kode Pos:</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code') }}">
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap (Patokan/Nama Jalan):</label>
                    <textarea name="specific_address" rows="3">{{ old('specific_address') }}</textarea>
                </div>

                <button type="submit">Simpan Alamat Sekarang</button>
            </form>
        </div>
        <!-- Bagian Form Tetap Sama, Hanya Script yang diperbaiki -->
        <script>
            $(document).ready(function() {
                function resetDropdowns(level) {
                    if (level === 'province') {
                        $('#city-select').prop('disabled', true).html('<option value="">Pilih Kabupaten/Kota</option>');
                        $('#district-select').prop('disabled', true).html('<option value="">Pilih Kecamatan</option>');
                        $('#village-select').prop('disabled', true).html(
                            '<option value="">Pilih Kelurahan/Desa</option>');
                    }
                    if (level === 'city') {
                        $('#district-select').prop('disabled', true).html('<option value="">Pilih Kecamatan</option>');
                        $('#village-select').prop('disabled', true).html(
                            '<option value="">Pilih Kelurahan/Desa</option>');
                    }
                    if (level === 'district') {
                        $('#village-select').prop('disabled', true).html(
                            '<option value="">Pilih Kelurahan/Desa</option>');
                    }
                }

                // 1. Pilih Provinsi -> Ambil Kota
                $('#province-select').change(function() {
                    var id = $(this).val();
                    var name = $(this).find('option:selected').text();
                    $('#province-name').val(name);
                    resetDropdowns('province');

                    if (id) {
                        $('#city-select').html('<option>Loading...</option>');
                        // URL disesuaikan dengan prefix /customer
                        $.get('/customer/get-cities/' + id, function(res) {
                            $('#city-select').prop('disabled', false).html(
                                '<option value="">Pilih Kabupaten/Kota</option>');
                            $.each(res, function(i, item) {
                                $('#city-select').append('<option value="' + item.id + '">' +
                                    item.name + '</option>');
                            });
                        });
                    }
                });

                // 2. Pilih Kota -> Ambil Kecamatan
                $('#city-select').change(function() {
                    var id = $(this).val();
                    var name = $(this).find('option:selected').text();
                    $('#city-name').val(name);
                    resetDropdowns('city');

                    if (id) {
                        $('#district-select').html('<option>Loading...</option>');
                        $.get('/customer/get-districts/' + id, function(res) {
                            $('#district-select').prop('disabled', false).html(
                                '<option value="">Pilih Kecamatan</option>');
                            $.each(res, function(i, item) {
                                $('#district-select').append('<option value="' + item.id +
                                    '">' + item.name + '</option>');
                            });
                        });
                    }
                });

                // 3. Pilih Kecamatan -> Ambil Desa
                // 3. Pilih Kecamatan -> Ambil Desa (Kelurahan/Sub-district)
                $('#district-select').change(function() {
                    var id = $(this).val(); // ID Kecamatan
                    var name = $(this).find('option:selected').text();
                    $('#district-name').val(name);
                    resetDropdowns('district');

                    if (id) {
                        console.log("Mencari sub-district untuk ID Kecamatan: " + id);
                        $('#village-select').html('<option>Loading Kelurahan...</option>');

                        $.get("{{ url('customer/get-villages') }}/" + id, function(res) {
                            console.log("Respon API Sub-district:", res); // LIHAT DI CONSOLE F12

                            $('#village-select').prop('disabled', false).html(
                                '<option value="">Pilih Kelurahan/Desa</option>');

                            if (res && res.length > 0) {
                                $.each(res, function(i, item) {
                                    // Cek kunci yang tersedia: item.name atau item.subdistrict_name
                                    var villageName = item.name || item.subdistrict_name || item
                                        .m_subdistrict_name;

                                    if (villageName) {
                                        $('#village-select').append('<option value="' +
                                            villageName + '">' + villageName + '</option>');
                                    }
                                });
                            } else {
                                $('#village-select').html(
                                    '<option value="">Data tidak ditemukan</option>');
                            }
                        }).fail(function(xhr) {
                            console.log("Error: " + xhr.status);
                            $('#village-select').html('<option value="">Gagal memuat data</option>');
                        });
                    }
                });
            });
        </script>
    </body>

    </html>
