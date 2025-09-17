<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>


  <div class="py-12 px-3">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight my-3 flex justify-between">
        {{ __('Books') }}
        <a href=" {{route('books.create')}}">
        <x-primary-button>
          Add
        </x-primary-button>
        </a>
      </h2>
      <article class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

        <table class="table-auto min-w-full text-left text-sm font-light text-surface dark:text-white">
          <thead class="border-b border-neutral-200 font-medium dark:border-white/10">
            <tr>
              <th scope="col" class="px-6 py-4">Title</th>
              <th scope="col" class="px-6 py-4">ISBN/Published</th>
              <th scope="col" class="px-6 py-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($books as $book)
        <tr class="border-b border-neutral-200 dark:border-white/10">
          <td class="px-3 py-4"><a href="{{route('books.show', $book)}}">{{$book->title}}</a></td>
          <td>{{$book->isbn}} <br><small>{{$book->published_at}}</small></td>
          <td class="px-3 text-right">


          <a href="{{route('books.show', $book)}}">
            <x-primary-button>
            {{ __('View') }}
            </x-primary-button>
          </a>

          <a href="{{route('books.destroy', $book)}}" data-confirm-delete="true">
            <x-secondary-button>
            {{ __('Delete') }}
            </x-secondary-button>
          </a>

          </td>
        </tr>
      @endforeach

          </tbody>
        </table>

      </article>
    </div>
  </div>
  </div>
</x-app-layout>