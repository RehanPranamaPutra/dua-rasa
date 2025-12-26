<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alamat</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Menggunakan Tailwind CSS jika tersedia di lingkungan Anda */
        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 1px solid #ccc; /* Tambahkan border agar lebih jelas */
            border-radius: 4px;
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 0.9em;
        }

        .alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }

        .error-message {
            color: red;
            font-size: 0.8em;
            margin-top: 4px;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <div style="width: 50%; min-width: 300px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2>Edit Alamat 🏠</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customer.address.update', $address->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Data Pengguna --}}
            <div class="form-group">
                <label for="customer_name">Nama Penerima:</label>
                <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name', $address->customer_name) }}">
                @error('customer_name') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="no_telp">Nomor Telepon:</label>
                <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp', $address->no_telp) }}">
                @error('no_telp') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <hr style="margin: 20px 0;">
            <h3>Data Wilayah</h3>

            {{-- Provinsi --}}
            <div class="form-group">
                <label for="province-select">Provinsi:</label>
                <select id="province-select" name="province_id">
                    <option value="">Pilih Provinsi</option>
                    @foreach (\Laravolt\Indonesia\Models\Provinsi::all() as $province)
                        <option value="{{ $province->code }}"
                            {{ old('province_id') == $province->code || $address->province == $province->name ? 'selected' : '' }}>
                            {{ $province->name }}
                        </option>
                    @endforeach
                </select>
                <input type="hidden" id="province-name" name="province" value="{{ old('province', $address->province) }}">
                @error('province') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            {{-- Kota --}}
            <div class="form-group">
                <label for="city-select">Kabupaten/Kota:</label>
                <select id="city-select" name="city_id">
                    <option value="">Pilih Kabupaten/Kota</option>
                    {{-- Opsi akan diisi oleh AJAX --}}
                </select>
                <input type="hidden" id="city-name" name="city" value="{{ old('city', $address->city) }}">
                @error('city') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            {{-- Kecamatan --}}
            <div class="form-group">
                <label for="district-select">Kecamatan:</label>
                <select id="district-select" name="subdistrict_id">
                    <option value="">Pilih Kecamatan</option>
                    {{-- Opsi akan diisi oleh AJAX --}}
                </select>
                <input type="hidden" id="district-name" name="subdistrict" value="{{ old('subdistrict', $address->subdistrict) }}">
                @error('subdistrict') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            {{-- Kelurahan --}}
            <div class="form-group">
                <label for="village-select">Kelurahan/Desa:</label>
                <select id="village-select" name="village_id">
                    <option value="">Pilih Kelurahan/Desa</option>
                    {{-- Opsi akan diisi oleh AJAX --}}
                </select>
                <input type="hidden" id="village-name" name="village" value="{{ old('village', $address->village) }}">
                @error('village') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="postal_code">Kode Pos:</label>
                <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $address->postal_code) }}">
                @error('postal_code') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="specific_address">Alamat Lengkap:</label>
                <textarea id="specific_address" name="specific_address" rows="3">{{ old('specific_address', $address->specific_address) }}</textarea>
                @error('specific_address') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Perbarui Alamat</button>
        </form>
    </div>

    {{-- Script Dinamis Wilayah --}}
    <script>
        $(document).ready(function() {
            // --- VARIABEL LAMA DARI DATA MODEL ---
            const oldProvinceName = "{{ $address->province }}";
            const oldCityName = "{{ $address->city }}";
            const oldDistrictName = "{{ $address->subdistrict }}";
            const oldVillageName = "{{ $address->village }}";

            // --- FUNGSI UTILITY UNTUK MENDAPATKAN KODE BERDASARKAN NAMA WILAYAH LAMA ---
            // Ini akan memastikan dropdown wilayah terisi dan terpilih dengan benar saat pertama kali dimuat.
            function findCodeByOldName(selectElementId, oldName, nextLoadFunction) {
                const selectElement = $(selectElementId);
                let foundCode = null;

                // Loop melalui opsi untuk menemukan kode yang sesuai dengan nama lama
                selectElement.find('option').each(function() {
                    if ($(this).text().trim() === oldName) {
                        foundCode = $(this).val();
                        selectElement.val(foundCode).change(); // Set value dan trigger change untuk load selanjutnya
                        return false; // break the loop
                    }
                });

                // Jika kode ditemukan, load data wilayah selanjutnya
                if (foundCode && nextLoadFunction) {
                    nextLoadFunction(foundCode, oldName);
                }
            }

            // --- INITIAL LOAD UNTUK KOTA DAN SETERUSNYA ---
            const initialProvinceCode = $("#province-select").val();
            if (initialProvinceCode) {
                // Jika sudah ada yang terpilih (berdasarkan old data), kita jalankan AJAX untuk KOTA
                loadCities(initialProvinceCode, oldCityName, oldDistrictName, oldVillageName);
            } else if (oldProvinceName) {
                // Ini untuk mengeset ulang jika old('province_id') tidak tersedia, tapi province name ada
                 findCodeByOldName("#province-select", oldProvinceName, loadCities);
            }

            // --- EVENT LISTENERS ---
            $('#province-select').on('change', function() {
                const code = $(this).val();
                const name = $(this).find("option:selected").text().trim();
                $('#province-name').val(name);
                loadCities(code, "", "", ""); // Reset pemilihan saat berubah
            });

            $('#city-select').on('change', function() {
                const code = $(this).val();
                const name = $(this).find("option:selected").text().trim();
                $('#city-name').val(name);
                loadDistricts(code, "", ""); // Reset pemilihan saat berubah
            });

            $('#district-select').on('change', function() {
                const code = $(this).val();
                const name = $(this).find("option:selected").text().trim();
                $('#district-name').val(name);
                loadVillages(code, ""); // Reset pemilihan saat berubah
            });

            $('#village-select').on('change', function() {
                const name = $(this).find("option:selected").text().trim();
                $('#village-name').val(name);
            });

            // === Functions ===
            function loadCities(provinceCode, selectedCityName, oldDistrictName, oldVillageName) {
                if (!provinceCode) {
                    $('#city-select, #district-select, #village-select').empty().append('<option value="">Pilih...</option>');
                    return;
                }
                $.ajax({
                    url: "{{ route('api.address.cities') }}",
                    data: { province_id: provinceCode },
                    success: function(cities) {
                        $('#city-select').empty().append('<option value="">Pilih Kabupaten/Kota</option>');
                        let cityCodeToLoad = null;
                        $.each(cities, function(_, city) {
                            const selected = city.name === selectedCityName ? 'selected' : '';
                            $('#city-select').append(`<option value="${city.code}" ${selected}>${city.name}</option>`);
                            if (selected) cityCodeToLoad = city.code;
                        });

                        // Jika ada nama kota lama, load data kecamatan
                        if (cityCodeToLoad && selectedCityName) {
                            loadDistricts(cityCodeToLoad, oldDistrictName, oldVillageName);
                        } else {
                            // Kosongkan yang selanjutnya jika tidak ada kota terpilih
                            $('#district-select, #village-select').empty().append('<option value="">Pilih...</option>');
                        }
                    }
                });
            }

            function loadDistricts(cityCode, selectedDistrictName, oldVillageName) {
                if (!cityCode) {
                    $('#district-select, #village-select').empty().append('<option value="">Pilih...</option>');
                    return;
                }
                $.ajax({
                    url: "{{ route('api.address.districts') }}",
                    data: { city_id: cityCode },
                    success: function(data) {
                        $('#district-select').empty().append('<option value="">Pilih Kecamatan</option>');
                        let districtCodeToLoad = null;
                        $.each(data, function(_, item) {
                            const selected = item.name === selectedDistrictName ? 'selected' : '';
                            $('#district-select').append(`<option value="${item.code}" ${selected}>${item.name}</option>`);
                            if (selected) districtCodeToLoad = item.code;
                        });

                        // Jika ada nama kecamatan lama, load data kelurahan
                        if (districtCodeToLoad && selectedDistrictName) {
                            loadVillages(districtCodeToLoad, oldVillageName);
                        } else {
                             // Kosongkan yang selanjutnya jika tidak ada kecamatan terpilih
                            $('#village-select').empty().append('<option value="">Pilih...</option>');
                        }
                    }
                });
            }

            function loadVillages(districtCode, selectedVillageName) {
                if (!districtCode) {
                    $('#village-select').empty().append('<option value="">Pilih...</option>');
                    return;
                }
                $.ajax({
                    url: "{{ route('api.address.villages') }}",
                    data: { district_id: districtCode },
                    success: function(data) {
                        $('#village-select').empty().append('<option value="">Pilih Kelurahan/Desa</option>');
                        $.each(data, function(_, item) {
                            const selected = item.name === selectedVillageName ? 'selected' : '';
                            $('#village-select').append(`<option value="${item.code}" ${selected}>${item.name}</option>`);
                        });
                    }
                });
            }
        });
    </script>
</body>

</html>
