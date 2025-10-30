<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tambah Alamat</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

  <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-lg">
    <h2 class="text-2xl font-bold mb-6 text-center">Tambah Alamat Pengiriman</h2>

    @if(session('warning'))
      <div class="bg-yellow-100 text-yellow-700 p-3 rounded mb-4 text-center">
        {{ session('warning') }}
      </div>
    @endif

    <form action="{{ route('address.store') }}" method="POST" class="space-y-4">
      @csrf

      <div>
        <label class="block font-medium mb-1">Nama Lengkap</label>
        <input type="text" name="customer_name" class="w-full border rounded p-2" required>
      </div>

      <div>
        <label class="block font-medium mb-1">No. Telepon</label>
        <input type="text" name="no_telp" class="w-full border rounded p-2" required>
      </div>

      <div>
        <label class="block font-medium mb-1">Provinsi</label>
        <input type="text" name="province" class="w-full border rounded p-2" required>
      </div>

      <div>
        <label class="block font-medium mb-1">Kota</label>
        <input type="text" name="city" class="w-full border rounded p-2" required>
      </div>

      <div>
        <label class="block font-medium mb-1">Kecamatan</label>
        <input type="text" name="subdistrict" class="w-full border rounded p-2" required>
      </div>

      <div>
        <label class="block font-medium mb-1">Kode Pos</label>
        <input type="text" name="postal_code" class="w-full border rounded p-2" required>
      </div>

      <div>
        <label class="block font-medium mb-1">Alamat Lengkap</label>
        <textarea name="specific_address" rows="3" class="w-full border rounded p-2" required></textarea>
      </div>

      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded transition">
        Simpan Alamat
      </button>
    </form>
  </div>

</body>
</html>
