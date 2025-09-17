<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>


  <div class="py-12 px-3">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight my-3">
        {{ __('Edit Book') }}
      </h2>
      <article class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">

          <form method="POST" action="{{ route('books.update', $book) }}">
            @csrf
            @method('PATCH')

            <!-- Email Address -->
            <section class="mt-4">
              <x-input-label for="title" :value="__('Title')" />
              <x-text-input id="title" class="block mt-1 w-full" type="title" name="title" :value="$book->title" 
                required autofocus autocomplete="title" />
              <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </section>

            <section class="mt-4">
              <x-input-label for="isbn" :value="__('ISBN')" />
              <x-text-input id="isbn" class="block mt-1 w-full" type="isbn" name="isbn" :value="$book->isbn"
                required autofocus autocomplete="isbn" />
              <x-input-error :messages="$errors->get('isbn')" class="mt-2" />
            </section>

            <section class="mt-4">
              <x-input-label for="published_at" :value="__('Published on')" />
              <x-text-input id="published_at" class="block mt-1 w-full" type="published_at" name="published_at" :value="$book->published_at"
                required autofocus autocomplete="published_at" />
              <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
            </section>

            <section class="mt-4">
              <x-input-label for="summary" :value="__('Summary')" />
              <x-text-input id="summary" class="block mt-1 w-full" type="summary" name="summary" :value="$book->summary"
                required autofocus autocomplete="summary" />
              <x-input-error :messages="$errors->get('summary')" class="mt-2" />
            </section>

            <div class="flex items-center justify-end mt-4">
              <x-secondary-button class="ms-3" type="reset">
                {{ __('Clear') }}
              </x-secondary-button>
              <x-primary-button class="ms-3">
                {{ __('Submit') }}
              </x-primary-button>
            </div>
          </form>

        </div>
      </article>
    </div>
  </div>
</x-app-layout>