@props(['currentStep' => 1])

@php
  $steps = [
    ['number' => 1, 'label' => 'Košík'],
    ['number' => 2, 'label' => 'Doprava a platba'],
    ['number' => 3, 'label' => 'Dodacie údaje'],
  ];
@endphp

<div class="border-b border-gray-200 bg-white">
  <div class="max-w-7xl mx-auto px-4 py-5 flex items-center text-sm">
    @foreach ($steps as $i => $step)
      @if ($i > 0)
        <div class="flex-1 h-px bg-gray-300 mx-2 sm:mx-4"></div>
      @endif

      <div class="flex items-center gap-2 shrink-0">
        @if ($step['number'] < $currentStep)
          {{-- completed step --}}
          <div class="w-7 h-7 rounded-full bg-brand-dark flex items-center justify-center shrink-0">
            <img src="{{ asset('icons/check.svg') }}" class="w-4 h-4 brightness-0 invert" alt="" />
          </div>
          <span class="font-semibold text-brand-dark hidden sm:inline">{{ $step['label'] }}</span>
        @elseif ($step['number'] === $currentStep)
          {{-- current step --}}
          <div class="w-7 h-7 rounded-full bg-brand-dark flex items-center justify-center shrink-0">
            <span class="text-white text-xs font-bold">{{ $step['number'] }}</span>
          </div>
          <span class="font-semibold text-brand-dark hidden sm:inline">{{ $step['label'] }}</span>
        @else
          {{-- future step --}}
          <div class="w-7 h-7 rounded-full border-2 border-gray-300 flex items-center justify-center shrink-0">
            <span class="text-gray-400 text-xs font-bold">{{ $step['number'] }}</span>
          </div>
          <span class="text-gray-400 hidden sm:inline">{{ $step['label'] }}</span>
        @endif
      </div>
    @endforeach
  </div>
</div>
