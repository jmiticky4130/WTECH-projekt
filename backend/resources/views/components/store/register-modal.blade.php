<div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center px-4">
  <div class="bg-white w-full max-w-md relative shadow-xl">
    <button class="absolute top-4 right-4 text-gray-400 hover:text-brand-dark transition-colors text-xl leading-none">
      &#x2715;
    </button>

    <div class="px-5 py-8 sm:px-10 sm:py-10">
      <h2 class="text-xl font-bold text-center mb-7">Registrácia</h2>

      <form action="#" method="post" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1.5">
              Meno <span class="text-red-500">*</span>
            </label>
            <input
              type="text"
              name="first_name"
              placeholder="Meno"
              class="w-full border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:border-brand-dark"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">
              Priezvisko <span class="text-red-500">*</span>
            </label>
            <input
              type="text"
              name="last_name"
              placeholder="Priezvisko"
              class="w-full border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:border-brand-dark"
            />
          </div>
        </div>

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
          <label class="block text-sm font-medium mb-1.5">Telefón</label>
          <input
            type="tel"
            name="phone"
            placeholder="+421 9XX XXX XXX"
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
              placeholder="Minimálne 8 znakov"
              class="w-full border border-gray-300 px-4 py-2.5 pr-12 text-sm focus:outline-none focus:border-brand-dark"
            />
            <span class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer">
              <img src="{{ asset('icons/eye.svg') }}" class="w-5 h-5" alt="Zobraziť heslo" />
            </span>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">
            Potvrdenie hesla <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <input
              type="password"
              name="password_confirmation"
              placeholder="Zopakujte heslo"
              class="w-full border border-gray-300 px-4 py-2.5 pr-12 text-sm focus:outline-none focus:border-brand-dark"
            />
            <span class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer">
              <img src="{{ asset('icons/eye-closed.svg') }}" class="w-5 h-5" alt="Skryť heslo" />
            </span>
          </div>
        </div>

        <button
          type="submit"
          class="w-full bg-brand-dark hover:bg-brand-accent text-white font-bold text-sm tracking-widest py-4 transition-colors uppercase mt-2"
        >
          Registrovať sa
        </button>
      </form>

      <p class="text-sm text-center text-gray-500 mt-6">
        Už máte účet?
        <a href="#" class="text-brand-dark font-medium underline hover:text-brand-accent transition-colors">Prihláste sa</a>
      </p>
    </div>
  </div>
</div>
