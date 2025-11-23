<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Change Password</title>
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('images/logo_doeit.png') }}">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link rel="stylesheet" href="{{ asset('css/font.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="ff-sfRegular">

  <div class="container mt-5 d-flex justify-content-center">
    <div class="card card-login p-4 shadow-sm text-black">
      <img src="{{ asset('images/logo_doeit.png') }}" alt="" width="100" class="mb-3">
      <h3 class="mb-1 text-start ff-sfSemibold">Change Password</h3>
      <span class="mb-3 subtitle">Update your account password below.</span>

      <form action="{{ route('auth.updatePassword') }}" method="POST">
        @csrf

        <div class="input-group">
          <input type="password" id="current_password" name="current_password" placeholder=" " required>
          <label for="current_password">Current Password</label>
        </div>

        <div class="input-group">
          <input type="password" id="new_password" name="new_password" placeholder=" " required>
          <label for="new_password">New Password</label>
        </div>

        <div class="input-group">
          <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder=" " required>
          <label for="new_password_confirmation">Confirm New Password</label>
        </div>

        <button type="submit" class="btn btn-success mb-3 w-100">
          <span class="ff-sfSemibold">Update Password</span>
        </button>
      </form>

      <span class="text-center ff-sfRegular">
        <a href="{{ route('dashboard') }}" class="text-success ff-sfBold">← Back to Dashboard</a>
      </span>
    </div>
  </div>

</body>
</html>
