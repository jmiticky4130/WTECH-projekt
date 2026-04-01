@props([
  'title' => 'Bellura.sk',
  'subNavActive' => null,
])

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
  </head>
  <body class="bg-white text-brand-dark min-h-screen flex flex-col">
    <x-store.top-bar />
    <x-store.header />
    <x-store.nav-bar />
    <x-store.sub-nav :active="$subNavActive" />

    {{ $slot }}

    <x-store.footer />
  </body>
</html>
