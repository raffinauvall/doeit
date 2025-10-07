<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container mt-5">
    <div class="card p-4 shadow-sm">
      <h3 class="mb-3 text-center">Register</h3>
      <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="mb-3">
          <label>Nama Lengkap</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Konfirmasi Password</label>
          <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Daftar</button>
      </form>

      <p class="text-center mt-3">
        Sudah punya akun?
        <a href="/login" class="text-decoration-none">Login di sini</a>
      </p>
    </div>
  </div>

</body>
</html>
