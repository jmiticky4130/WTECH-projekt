@props([
  'title' => 'Bellura.sk',
  'subNavActive' => null,
])

@php
  if ($errors->has('first_name') || $errors->has('last_name') || $errors->has('password_confirmation')) {
    $initialModal = "'register'";
  } elseif ($errors->isNotEmpty()) {
    $initialModal = "'login'";
  } else {
    $initialModal = 'null';
  }
@endphp

<!doctype html>
<html lang="sk">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: { sans: ["DM Sans", "sans-serif"] },
            colors: {
              brand: {
                dark: "#444444",
                light: "#f5f5f5",
                accent: "#333333",
              },
            },
          },
        },
      };
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script>
      window.__bellura = {
        isAuth: {{ auth()->check() ? 'true' : 'false' }},
        csrfToken: '{{ csrf_token() }}',
      };
      function headerCart() {
        return {
          count: 0,
          async init() {
            if (window.__bellura.isAuth) {
              try {
                const r = await fetch('/cart/count', { headers: { Accept: 'application/json' } });
                const d = await r.json();
                this.count = d.count;
              } catch {}
            } else {
              const c = JSON.parse(localStorage.getItem('bellura_cart') || '[]');
              this.count = c.reduce((s, i) => s + i.qty, 0);
            }
          },
          onCartUpdated(e) {
            if (e.detail?.count !== undefined) {
              this.count = e.detail.count;
            } else {
              this.init();
            }
          },
        };
      }
    </script>
    <script>
      function searchBar() {
        return {
          query: '',
          results: [],
          open: false,
          _timer: null,

          onInput(value) {
            this.query = value;
            clearTimeout(this._timer);
            if (value.length < 2) {
              this.results = [];
              this.open = false;
              return;
            }
            this._timer = setTimeout(() => this.fetchSuggestions(), 300);
          },

          async fetchSuggestions() {
            try {
              const r = await fetch('/search-suggestions?q=' + encodeURIComponent(this.query));
              const data = await r.json();
              this.results = data;
              this.open = data.length > 0;
            } catch {}
          },

          close() {
            this.open = false;
          },
        };
      }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
  </head>
  <body x-data="{ modal: {{ $initialModal }} }" class="bg-white text-brand-dark min-h-screen flex flex-col">
    <x-store.top-bar />
    <x-store.header />
    <x-store.nav-bar />
    <x-store.sub-nav :active="$subNavActive" />

    {{ $slot }}

    <x-store.footer />

    <x-store.login-modal />
    <x-store.register-modal />
  </body>
</html>
