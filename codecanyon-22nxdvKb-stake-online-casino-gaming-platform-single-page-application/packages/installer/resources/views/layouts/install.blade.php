<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <title>{{ config('app.name') }} | Installation</title>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body class="d-flex flex-column vh-100">
  <nav class="navbar bg-body-tertiary">
    <div class="container">
      <a class="navbar-brand" href="https://1stake.app" target="_blank">
        {{ config('app.name') }} platform
      </a>
    </div>
  </nav>

  <div id="app">
    <div class="container">
      <div class="row mt-3 mb-3">
        <div class="col">
          <h2>{{ $title }}</h2>
        </div>
      </div>
      @if ($errors->any())
        <div class="row mt-3 mb-3">
          <div class="col">
            <div class="alert alert-danger">
              <h4 class="alert-heading">
                Error:
              </h4>
              <ul>
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      @endif
      <div class="row">
        <div class="col">
          @yield('content')
        </div>
      </div>
    </div>
  </div>

  <footer class="footer mt-auto py-3 bg-body-secondary">
    <div class="container">
      &copy; <a href="https://1stake.app" target="_blank">1stake.app</a>
    </div>
  </footer>

  @stack('scripts')
</body>
</html>
