<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
// use RealRashid\SweetAlert\Facades\Alert;
use Validator;
use Redirect;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // echo "Hello from Book Controller > index method";
        $books = Book::all();
        confirmDelete('Delete Book!',"Are you sure you want to delete?");
        return view("books.index", ['books' => $books]);

        /*
        $books = Book::paginate(10);
        $books_count = Book::all()->count();
        return view('books.index', compact('books', 'books_count')); 
        */
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // alert('Title','This is the sample body', 'success');
        // alert()->success('SuccessAlert','Lorem ipsum dolor sit amet.');



        return view("books.create");

        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => 'required|string|min:3|max:50',
            "isbn" => 'required|max:15|unique:books',
            "summary" => 'required|string|min:3|max:50',
            "published_at" => 'required|date',
        ]);

        if ($validator->fails()) {
            // return response()->json(["message" => $validator->errors()->all()], 400);
            return Redirect::back()->withErrors($validator);

        }
        
        // Define your custom parameters
        $customData = [
            'title' => $request->input('title', 'Default Title'),
            'isbn' => $request->input('isbn', '123-456-789'),
            'summary' => $request->input('summary', 'Default Summary'),
            'published_at' => $request->input('published_at', now()),
        ];

        // Create a new book with the custom data
        $book = Book::factory()->create($customData);

        // return response()->json($book);
        toast('Book added!','success');

        return redirect()->route('books.index')
                         ->with('success', 'Book added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view("books.view", ['book' => $book]);

        // echo $book;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return view("books.edit", ['book' => $book]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        // dd("update");
        //$item = Item::findOrFail($id);
        $validator = Validator::make($request->all(), [
            "title" => 'required|string|min:3|max:50',
            "isbn" => 'required|max:15|unique:books',
            "summary" => 'required|string|min:3|max:50',
            "published_at" => 'required|date',
        ]);

        if ($validator->fails()) {
            // return response()->json(["message" => $validator->errors()->all()], 400);
            return Redirect::back()->withErrors($validator);

        }

        $book = Book::where("id", $book->id)->update([
            "title" => $request->title,
            "isbn" => $request->isbn,
            "summary" => $request->summary,
            "published_at" => $request->published_at,
        ]);

        toast('Book updated!','success');
        return redirect()->route('books.index')
                         ->with('success', 'Book updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        toast('Book deleted!','success');

        return redirect()->route('books.index')
                         ->with('success', 'Book deleted successfully');
    }
}
