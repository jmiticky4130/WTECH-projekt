@props(['title' => 'Admin — Bellura.sk', 'active' => 'products'])

<!doctype html>
<html lang="sk">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
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
                dark: "#5C4530",
                light: "#f5f5f5",
                accent: "#4A3525",
              },
            },
            screens: {
              wide: "900px",
            },
          },
        },
      };
    </script>
  </head>
  <body class="font-sans bg-gray-100 min-h-screen">
    <x-admin.mobile-nav :active="$active" />
    <div class="flex min-h-screen">
      <x-admin.sidebar :active="$active" />
      <main class="flex-1 overflow-auto">
        {{ $slot }}
      </main>
    </div>
  </body>
</html>
