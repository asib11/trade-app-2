@extends('installer::layouts.install')

@section('content')
  <p>Database is successfully set up! Please input admin user details below.</p>
  <form class="ui form" method="POST" action="{{ route('install.process', ['step' => $step]) }}">
    @csrf
    <div class="form-group mb-3">
      <label>Name</label>
      <input type="text" name="name" placeholder="Name" class="form-control" value="{{ old('name') }}" required>
    </div>
    <div class="form-group mb-3">
      <label>Email</label>
      <input type="email" name="email" placeholder="Email" class="form-control" value="{{ old('email') }}" required>
    </div>
    <div class="form-group mb-3">
      <label>Password</label>
      <input type="password" name="password" placeholder="Password" class="form-control" value="{{ old('password') }}" required>
    </div>
    <div>
      <x-installer::submit-button />
    </div>
  </form>
@endsection
