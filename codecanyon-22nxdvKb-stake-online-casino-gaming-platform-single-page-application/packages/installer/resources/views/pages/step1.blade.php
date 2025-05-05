@extends('installer::layouts.install')

@section('content')
  <p>Please input your product license.</p>
  <form class="ui form" method="POST" action="{{ route('install.process', ['step' => $step]) }}">
    @csrf
    <div class="form-group mb-3">
      <label>Purchase code</label>
      <input type="text" name="code" placeholder="Purchase code" class="form-control" value="{{ old('code', env(FP_CODE)) }}" required>
    </div>
    <div class="form-group mb-3">
      <label>License holder email</label>
      <input type="text" name="email" placeholder="Email" class="form-control" value="{{ old('email', env(FP_EMAIL)) }}" required>
    </div>
    <div>
      <x-installer::submit-button />
    </div>
  </form>
@endsection

