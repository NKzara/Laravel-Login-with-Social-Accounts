<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>


  <div class="py-12 px-3">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight my-3 flex justify-between">
        {{ __('View Book') }}
        <a href=" {{route('books.edit', $book)}}">
        <x-primary-button>
          Edit
        </x-primary-button>
        </a>
      </h2>
      <article class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <article__ class="p-6 text-gray-900 dark:text-gray-100">

          <table class="table-auto min-w-full text-left text-sm font-light text-surface dark:text-white">
            <tbody>
              <tr class="border-b border-neutral-200 dark:border-white/10">
                <th scope="col" class="px-6 py-4 text-right">Title : </th>
                <td class="px-3 py-4"><a href="{{route('books.show', $book)}}">{{$book->title}}</a></td>
              </tr>
              <tr class="border-b border-neutral-200 dark:border-white/10">
                <th scope="col" class="px-6 py-4 text-right">ISBN : </th>
                <td>{{$book->isbn}}</td>
              </tr>
              <tr class="border-b border-neutral-200 dark:border-white/10">
                <th scope="col" class="px-6 py-4 text-right">Published : </th>
                <td>{{$book->published_at}}</td>
              </tr>
              <tr class="border-b border-neutral-200 dark:border-white/10">
                <th scope="col" class="px-6 py-4 text-right">Summary : </th>
                <td>{{$book->summary}}</td>
              </tr>
            </tbody>
          </table>

        </article__>
      </article>
    </div>
  </div>
</x-app-layout>