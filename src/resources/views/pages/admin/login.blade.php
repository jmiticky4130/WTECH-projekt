<!doctype html>
<html lang="sk">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin prihlásenie — Bellura.sk</title>
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
  <body class="bg-brand-dark min-h-screen flex flex-col items-center justify-center px-4 font-sans">

    <div class="bg-white w-full max-w-md shadow-xl">
      <div class="px-8 py-10">

        <div class="flex justify-center mb-8">
          <a href="{{ route('home') }}">
            <img src="{{ asset('images/brand-logo.png') }}" alt="Bellura" class="h-12">
          </a>
        </div>

        <h1 class="text-xl font-bold text-center mb-1">Administrátorské prihlásenie</h1>
        <p class="text-sm text-gray-500 text-center mb-7">Prihláste sa do administračného rozhrania.</p>

        <form action="#" method="post" class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1.5">
              E-mail <span class="text-red-500">*</span>
            </label>
            <input
              type="email"
              placeholder="admin@bellura.sk"
              class="w-full border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:border-brand-dark"
            />
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">
              Heslo <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <input
                type="password"
                placeholder="••••••••"
                class="w-full border border-gray-300 px-4 py-2.5 pr-12 text-sm focus:outline-none focus:border-brand-dark"
              />
              <span class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer select-none text-gray-400 hover:text-brand-dark transition-colors">
                <img src="{{ asset('icons/eye.svg') }}" class="w-5 h-5" alt="Zobraziť heslo" />
              </span>
            </div>
          </div>

          <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
            <input type="checkbox" class="w-4 h-4 accent-brand-dark" />
            Zapamätať si ma
          </label>

          <button
            type="submit"
            class="w-full bg-brand-dark hover:bg-brand-accent text-white font-bold text-sm tracking-widest py-4 transition-colors uppercase mt-2"
          >
            Prihlásiť sa
          </button>
        </form>

        <p class="text-sm text-center text-gray-400 mt-6">
          <a href="{{ route('home') }}" class="hover:text-brand-dark transition-colors underline">
            ← Späť na obchod
          </a>
        </p>
      </div>
    </div>

    <p class="text-gray-600 text-xs mt-4">© 2026 Bellura s.r.o.</p>
  </body>
</html>
