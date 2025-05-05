<button id="submit-button" class="btn btn-primary">
  <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
  <span class="button-text">{{ $text }}</span>
</button>

@push('scripts')
  <script>
  const form = document.querySelector('form');

  form.addEventListener('submit', (event) => {
    event.preventDefault();
    toggleLoadingState();
    form.submit();
  });

  function toggleLoadingState() {
    const button = document.getElementById('submit-button');
    const spinner = button.querySelector('.spinner-border');

    if (spinner.classList.contains('d-none')) {
      spinner.classList.remove('d-none');
      button.disabled = true;
    }
  }
  </script>
@endpush
