@extends('installer::layouts.install')

@section('content')
  <p>Congratulations! Installation is completed and the <b>{{ config('app.name') }}</b> platform is now up and running.</p>
  <p>
    To ensure the platform and all games function correctly,
    please add the following cron job to your server that runs <b>every minute</b>:
  </p>
  <div class="alert alert-info mb-3">
    <pre class="mb-0">{{ \App\Helpers\Utils::getCronJobCommand() }}</pre>
  </div>
  <a href="/" class="btn btn-primary" target="_blank">Home page</a>
@endsection
