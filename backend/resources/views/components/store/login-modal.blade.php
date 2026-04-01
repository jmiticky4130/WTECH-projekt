<div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center px-4">
  <div class="bg-white w-full max-w-md relative shadow-xl">
    <button class="absolute top-4 right-4 text-gray-400 hover:text-brand-dark transition-colors text-xl leading-none">
      &#x2715;
    </button>

    <div class="px-5 py-8 sm:px-10 sm:py-10">
      <h2 class="text-xl font-bold text-center mb-1">Prihlásenie</h2>
      <p class="text-sm text-gray-500 text-center mb-7">
        Vitajte späť! Prihláste sa do svojho účtu.
      </p>

      <form action="#" method="post" class="space-y-4">
        @csrf
        <div>
          <label class="block text-sm font-medium mb-1.5">
            E-mail <span class="text-red-500">*</span>
          </label>
          <input
            type="email"
            name="email"
            placeholder="vas@email.sk"
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
              name="password"
              placeholder="••••••••"
              class="w-full border border-gray-300 px-4 py-2.5 pr-12 text-sm focus:outline-none focus:border-brand-dark"
            />
            <span class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer">
              <img src="{{ asset('icons/eye.svg') }}" class="w-5 h-5" alt="Zobraziť heslo" />
            </span>
          </div>
        </div>

        <div class="flex items-center">
          <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
            <input type="checkbox" name="remember" class="w-4 h-4 accent-brand-dark" />
            Zapamätať si ma
          </label>
        </div>

        <button
          type="submit"
          class="w-full bg-brand-dark hover:bg-brand-accent text-white font-bold text-sm tracking-widest py-4 transition-colors uppercase mt-2"
        >
          Prihlásiť sa
        </button>
      </form>

      <p class="text-sm text-center text-gray-500 mt-6">
        Nemáte účet?
        <a href="#" class="text-brand-dark font-medium underline hover:text-brand-accent transition-colors">Zaregistrujte sa</a>
      </p>
    </div>
  </div>
</div>
