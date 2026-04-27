<div x-show="modal === 'login'" x-cloak class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center px-4">
  <div class="bg-white w-full max-w-md relative shadow-xl" @click.stop>
    <button @click="modal = null" class="absolute top-4 right-4 text-gray-400 hover:text-brand-dark transition-colors text-xl leading-none">
      &#x2715;
    </button>

    <div class="px-5 py-8 sm:px-10 sm:py-10">
      <h2 class="text-xl font-bold text-center mb-1">Prihlásenie</h2>
      <p class="text-sm text-gray-500 text-center mb-7">
        Vitajte späť! Prihláste sa do svojho účtu.
      </p>

      @if ($errors->has('email') && !$errors->has('first_name') && !$errors->has('last_name'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 mb-4">
          {{ $errors->first('email') }}
        </div>
      @endif

      <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf
        <div>
          <label class="block text-sm font-medium mb-1.5">
            E-mail <span class="text-red-500">*</span>
          </label>
          <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="vas@email.sk"
            class="w-full border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:border-brand-dark"
          />
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">
            Heslo <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <div data-password-container class="relative">
            <input
              type="password"
              name="password"
              placeholder="••••••••"
              class="w-full border border-gray-300 px-4 py-2.5 pr-12 text-sm focus:outline-none focus:border-brand-dark"
            />
            <button
              type="button"
              data-password-toggle
              data-password-show-icon="{{ asset('icons/eye.svg') }}"
              data-password-hide-icon="{{ asset('icons/eye-closed.svg') }}"
              class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer"
              aria-label="Zobraziť heslo"
              aria-pressed="false"
            >
              <img data-password-icon src="{{ asset('icons/eye.svg') }}" class="w-5 h-5" alt="Zobraziť heslo" />
            </button>
            </div>
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
        <a href="#" @click.prevent="modal = 'register'" class="text-brand-dark font-medium underline hover:text-brand-accent transition-colors">Zaregistrujte sa</a>
      </p>
    </div>
  </div>
</div>
