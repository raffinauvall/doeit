<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('images/logo_doeit.png') }}">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link rel="stylesheet" href="{{ asset('css/font.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="ff-sfRegular">

  <div class="container mt-5 d-flex justify-content-center flex-column align-items-center" style="max-width: 400px;">
    
    {{-- ERROR VALIDASI --}}
    @if ($errors->any())
      <div class="alert alert-danger w-100 mb-3">
        <strong>An error occurred:</strong>
        <ul class="mb-0 mt-2">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="card card-login p-4 shadow-sm text-black">
      <img src="{{ asset('images/logo_doeit.png') }}" alt="" width="100" class="mb-3">
      <h3 class="mb-1 text-start ff-sfSemibold">Sign Up</h3>
      <span class="mb-3 subtitle">Create your account.</span>

      <form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="input-group">
          <input type="text" id="name" name="name" placeholder=" " value="{{ old('name') }}" required>
          <label for="name">full name</label>
        </div>

        <div class="input-group">
          <input type="email" id="email" name="email" placeholder=" " value="{{ old('email') }}" required>
          <label for="email">e-mail address</label>
        </div>

        <div class="input-group">
          <input type="password" id="password" name="password" placeholder=" " required>
          <label for="password">password</label>
        </div>

        {{-- ✅ Tambahan field konfirmasi password --}}
        <div class="input-group">
          <input type="password" id="password_confirmation" name="password_confirmation" placeholder=" " required>
          <label for="password_confirmation">confirm password</label>
        </div>

        <div class="input-group select-group">
          <select id="gender" name="gender" required>
            <option value="" disabled selected hidden></option>
            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
          </select>
          <label for="gender">gender</label>
        </div>

        <button type="submit" class="btn btn-success mb-3 w-100"><span class="ff-sfSemibold">Register</span></button>
      </form>

      <span class="text-center ff-sfRegular mb-2">
        Already have an account? <a href="/login" class="text-success ff-sfBold">Sign In</a>
      </span>
    </div>
  </div>

</body>
</html>
