<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Alamat</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }

        .alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
        }
    </style>
</head>

<body>

    <div style="width: 50%; margin: 50px auto; padding: 20px; border: 1px solid #ccc;">
        <h2>Tambah Alamat Baru 🗺️</h2>

        {{-- Tampilkan Pesan Error Jika Ada --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Pesan sukses (jika ingin ditambahkan flash message di controller) --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form Tambah Alamat --}}
        <form action="{{ route('customer.address.store') }}" method="POST">
            @csrf

            {{-- Data Pengguna --}}
            <div class="form-group">
                <label for="customer_name">Nama Penerima:</label>
                <input type="text" id="customer_name" name="customer_name">
            </div>

            <div class="form-group">
                <label for="no_telp">Nomor Telepon:</label>
                <input type="text" id="no_telp" name="no_telp">
            </div>

            <hr>
            <h3>Data Wilayah</h3>

            {{-- Dropdown Provinsi --}}
            <div class="form-group">
                <label for="province">Provinsi:</label>
                <select id="province-select" name="province_id">
                    <option value="">Pilih Provinsi</option>
                    @foreach (\Laravolt\Indonesia\Models\Provinsi::all() as $province)
                        <option value="{{ $province->code }}">{{ $province->name }}</option>
                    @endforeach
                </select>
                <input type="hidden" id="province-name" name="province">
            </div>

            {{-- Dropdown Kabupaten/Kota --}}
            <div class="form-group">
                <label for="city">Kabupaten/Kota:</label>
                <select id="city-select" name="city_id" disabled>
                    <option value="">Pilih Kabupaten/Kota</option>
                </select>
                <input type="hidden" id="city-name" name="city">
            </div>

            {{-- Dropdown Kecamatan --}}
            <div class="form-group">
                <label for="subdistrict">Kecamatan:</label>
                <select id="district-select" name="subdistrict_id" disabled>
                    <option value="">Pilih Kecamatan</option>
                </select>
                <input type="hidden" id="district-name" name="subdistrict">
            </div>

            {{-- Dropdown Kelurahan/Desa --}}
            <div class="form-group">
                <label for="village">Kelurahan/Desa:</label>
                <select id="village-select" name="village_id" disabled>
                    <option value="">Pilih Kelurahan/Desa</option>
                </select>
                <input type="hidden" id="village-name" name="village">
            </div>

            {{-- Data Alamat Detail --}}
            <div class="form-group">
                <label for="postal_code">Kode Pos:</label>
                <input type="text" id="postal_code" name="postal_code">
            </div>

            <div class="form-group">
                <label for="specific_address">Alamat Lengkap (Jalan, RT/RW, Patokan):</label>
                <textarea id="specific_address" name="specific_address" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Alamat</button>
        </form>
    </div>

    {{-- JavaScript Wilayah --}}
    <script>
        $(document).ready(function() {

            function resetAllDependents() {
                $('#city-select').prop('disabled', true).empty().append(
                    '<option value="">Pilih Kabupaten/Kota</option>');
                $('#district-select').prop('disabled', true).empty().append(
                    '<option value="">Pilih Kecamatan</option>');
                $('#village-select').prop('disabled', true).empty().append(
                    '<option value="">Pilih Kelurahan/Desa</option>');
                $('#city-name').val('');
                $('#district-name').val('');
                $('#village-name').val('');
            }

            $('#province-select').on('change', function() {
                var provinceCode = $(this).val();
                var provinceName = $(this).find('option:selected').text();

                $('#province-name').val(provinceName);
                resetAllDependents();

                if (provinceCode) {
                    $('#city-select').empty().append('<option value="">Loading...</option>');
                    $.ajax({
                        url: '{{ route('api.address.cities') }}',
                        type: 'GET',
                        data: { province_id: provinceCode },
                        dataType: 'json',
                        success: function(cities) {
                            $('#city-select').prop('disabled', false).empty().append('<option value="">Pilih Kabupaten/Kota</option>');
                            $.each(cities, function(key, value) {
                                $('#city-select').append('<option value="' + value.code + '">' + value.name + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            alert('Terjadi kesalahan saat memuat data Kabupaten/Kota: ' + error);
                        }
                    });
                }
            });

            $('#city-select').on('change', function() {
                var cityCode = $(this).val();
                var cityName = $(this).find('option:selected').text();
                $('#city-name').val(cityName);
                $('#district-select').prop('disabled', true).empty().append('<option value="">Loading...</option>');
                $('#village-select').prop('disabled', true).empty().append('<option value="">Pilih Kelurahan/Desa</option>');
                $('#district-name').val('');
                $('#village-name').val('');

                if (cityCode) {
                    $.ajax({
                        url: '{{ route('api.address.districts') }}',
                        type: 'GET',
                        data: { city_id: cityCode },
                        dataType: 'json',
                        success: function(districts) {
                            $('#district-select').prop('disabled', false).empty().append('<option value="">Pilih Kecamatan</option>');
                            $.each(districts, function(key, value) {
                                $('#district-select').append('<option value="' + value.code + '">' + value.name + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            alert('Terjadi kesalahan saat memuat data Kecamatan: ' + error);
                        }
                    });
                }
            });

            $('#district-select').on('change', function() {
                var districtCode = $(this).val();
                var districtName = $(this).find('option:selected').text();
                $('#district-name').val(districtName);
                $('#village-select').prop('disabled', true).empty().append('<option value="">Loading...</option>');
                $('#village-name').val('');

                if (districtCode) {
                    $.ajax({
                        url: '{{ route('api.address.villages') }}',
                        type: 'GET',
                        data: { district_id: districtCode },
                        dataType: 'json',
                        success: function(villages) {
                            $('#village-select').prop('disabled', false).empty().append('<option value="">Pilih Kelurahan/Desa</option>');
                            $.each(villages, function(key, value) {
                                $('#village-select').append('<option value="' + value.code + '">' + value.name + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            alert('Terjadi kesalahan saat memuat data Kelurahan/Desa: ' + error);
                        }
                    });
                }
            });

            $('#village-select').on('change', function() {
                var villageName = $(this).find('option:selected').text();
                $('#village-name').val(villageName);
            });
        });
    </script>

</body>
</html>
