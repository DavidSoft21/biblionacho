<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\Books\BookCreateRequest;
use App\Http\Requests\Api\Books\BookUpdateRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Book;

class BookController extends Controller
{
    /**
     * 
     * * @OA\Schema(
     *     schema="Book",
     *     required={"isbn", "title", "author", "editorial", "edition", "year", "language", "pages"},
     *     @OA\Property(
     *         property="isbn",
     *         type="string",
     *         description="ISBN of the book",
     *         example="yI47e2b87c"
     *     ),
     *     @OA\Property(
     *         property="title",
     *         type="string",
     *         description="Title of the book",
     *         example="The Picture of Dorian Gray"
     *     ),
     *     @OA\Property(
     *         property="author",
     *         type="string",
     *         description="Author of the book",
     *         example="Oscar Wilde"
     *     ),
     *     @OA\Property(
     *         property="editorial",
     *         type="string",
     *         description="Editorial of the book",
     *         example="Lippincott's Monthly Magazine."
     *     ),
     *     @OA\Property(
     *         property="edition",
     *         type="string",
     *         description="Edition of the book",
     *         example="25"
     *     ),
     *     @OA\Property(
     *         property="year",
     *         type="string",
     *         description="Year of the book",
     *         example="1890"
     *     ),
     *     @OA\Property(
     *         property="language",
     *         type="string",
     *         description="Language of the book",
     *         example="en"
     *     ),
     *     @OA\Property(
     *         property="pages",
     *         type="string",
     *         description="Pages of the book",
     *         example="300"
     *     ),
     * ),
     * @OA\Get(
     *   path="/books/index",
     *   summary="Index book",
     *   description="Show books",
     *   operationId="IndexBook",
     *   tags={"Books"},
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Ok 200"),
     *       @OA\Property(property="book", type="object", ref="#/components/schemas/Book"),
     *     )
     *   ),
     * 
     *   @OA\Response(
     *     response=500,
     *     description="Internal Server Error",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Server Error"),
     *       @OA\Property(property="errors", type="object", example={"errors": {"Internal server error!"}}),
     *     )
     *   ),
     * )
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $books = Book::all();

            return response()->json([
                'message' => 'Ok 200',
                'books' => $books
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'errors' => ['errors' => 'Internal server error!']
            ], 500);
        }
    }


    /**
     * @OA\Post(
     *   path="/books/store",
     *   summary="Create books for admin",
     *   description="Create books",
     *   operationId="CreateBook",
     *   tags={"Books"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     description="Bearer token",
     *     @OA\Schema(
     *       type="string",
     *       default="Bearer id|YOUR_ACCESS_TOKEN_HERE"
     *    )
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     description="Book creation",
     *     @OA\JsonContent(
     *       required={"isbn","title","author","editorial","edition","year","language","pages"},
     *       @OA\Property(property="isbn", type="string", example="yI47e2b87c"),
     *       @OA\Property(property="title", type="string", example="The Picture of Dorian Gray"),
     *       @OA\Property(property="author", type="string", example="Oscar Wilde"),
     *       @OA\Property(property="editorial", type="string",  example="Lippincott's Monthly Magazine."),
     *       @OA\Property(property="edition", type="string",  example="25"),
     *       @OA\Property(property="year", type="string",  example="1890"),
     *       @OA\Property(property="language", type="string",  example="en"),
     *       @OA\Property(property="pages", type="string",  example="300"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Successfully registered",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Successfully registered"),
     *       @OA\Property(property="book", type="object", ref="#/components/schemas/Book"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validation Error",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Validation Error"),
     *       @OA\Property(property="errors", type="object", example={"title": {"The title field is required."}}),
     *     )
     *   ),
     * 
     * @OA\Response(
     *     response=500,
     *     description="Internal Server Error",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Server Error"),
     *       @OA\Property(property="errors", type="object", example={"errors": {"Internal server error!"}}),
     *     )
     *   ),
     * )
     *
     * @param BookCreateRequest $request
     * @return JsonResponse
     */
    public function store(BookCreateRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $book = Book::create([
                'isbn' => $validatedData['isbn'],
                'title' => $validatedData['title'],
                'author' => $validatedData['author'],
                'editorial' => $validatedData['editorial'],
                'edition' => $validatedData['edition'],
                'year' => $validatedData['year'],
                'language' => $validatedData['language'],
                'pages' => $validatedData['pages']
            ]);

            return response()->json([
                'message' => 'Successfully registered',
                'book' => $book
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }
    }


    /**
     * @OA\Get(
     *   path="/books/show/{id}",
     *   summary="Show book",
     *   description="Show a book by ID",
     *   operationId="ShowBook",
     *   tags={"Books"},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="User ID",
     *     @OA\Schema(
     *       type="integer"
     *    )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Ok 200"),
     *       @OA\Property(property="book", type="object", ref="#/components/schemas/Book"),
     *     )
     *   ),
     * 
     *   @OA\Response(
     *     response=422,
     *     description="Validation Error",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Validation Error"),
     *       @OA\Property(property="errors", type="object", example={"id": {"The id is required."}}),
     *     )
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Internal Server Error",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Server Error"),
     *       @OA\Property(property="errors", type="object", example={"errors": {"Internal server error!"}}),
     *     )
     *   ),
     * 
     *  @OA\Response(
     *     response=404,
     *     description="Not Found",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="NotFound"),
     *       @OA\Property(property="errors", type="object", example={"errors": {"The Book with the provided ID was not found."}}),
     *     )
     *   ),
     * )
     *
     * @param int $id Book ID to Show
     * @return JsonResponse
     */
    public function show(int $id)
    {
        try {
            $book = Book::findOrFail($id);

            return response()->json([
                'message' => 'Ok 200',
                'book' => $book
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'NotFound',
                'errors' => ['id' => 'The book with the provided ID was not found.']
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'errors' => ['errors' => 'Internal server error!']
            ], 500);
        }
    }


    /**
     * @OA\Put(
     *   path="/books/update/{id}",
     *   summary="Update books for admin",
     *   description="Update books",
     *   operationId="UpdateBook",
     *   tags={"Books"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     description="Bearer token",
     *     @OA\Schema(
     *       type="string",
     *       default="Bearer id|YOUR_ACCESS_TOKEN_HERE"
     *    )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Book ID",
     *     @OA\Schema(
     *       type="integer"
     *    )
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     description="User update",
     *     @OA\JsonContent(
     *      required={"isbn","title","author","editorial","edition","year","language","pages"},
     *       @OA\Property(property="isbn", type="string", example="yI47e2b87c"),
     *       @OA\Property(property="title", type="string", example="The Picture of Dorian Gray"),
     *       @OA\Property(property="author", type="string", example="Oscar Wilde"),
     *       @OA\Property(property="editorial", type="string",  example="Lippincott's Monthly Magazine."),
     *       @OA\Property(property="edition", type="string",  example="25"),
     *       @OA\Property(property="year", type="string",  example="1890"),
     *       @OA\Property(property="language", type="string",  example="en"),
     *       @OA\Property(property="pages", type="string",  example="300"),
     *    )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Successfully updated",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Successfully updated"),
     *       @OA\Property(property="book", type="object", ref="#/components/schemas/Book"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validation Error",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Validation Error"),
     *       @OA\Property(property="errors", type="object", example={"title": {"The title field is required."}}),
     *     )
     *   ),
     * 
     * @OA\Response(
     *     response=404,
     *     description="Not Found",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Not Found"),
     *       @OA\Property(property="errors", type="object", example={"errors": {"The Book with the provided ID was not found."}}),
     *     )
     *   ),
     * 
     * @OA\Response(
     *     response=500,
     *     description="Internal Server Error",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Server Error"),
     *       @OA\Property(property="errors", type="object", example={"errors": {"Internal server error!"}}),
     *     )
     *   ),
     * )
     *
     * @param BookUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(BookUpdateRequest $request, int $id)
    {
        try {
            $validatedData = $request->validated();

            $book = Book::find($id);

            $book->update([
                'isbn' => $validatedData['isbn'],
                'title' => $validatedData['title'],
                'author' => $validatedData['author'],
                'editorial' => $validatedData['editorial'],
                'edition' => $validatedData['edition'],
                'year' => $validatedData['year'],
                'language' => $validatedData['language'],
                'pages' => $validatedData['pages']
            ]);

            return response()->json([
                'message' => 'Successfully updated',
                'book' => $book
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * @OA\Delete(
     *   path="/books/destroy/{id}",
     *   summary="Delete Book",
     *   description="Delete a book by ID",
     *   operationId="BookDelete",
     *   tags={"Books"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     description="Bearer token",
     *     @OA\Schema(
     *       type="string",
     *       default="Bearer YOUR_ACCESS_TOKEN_HERE"
     *    )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Book ID",
     *     @OA\Schema(
     *       type="integer"
     *    )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Successfully deleted",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Successfully deleted"),
     *       @OA\Property(property="book", type="object", ref="#/components/schemas/Book"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validation Error",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Validation Error"),
     *       @OA\Property(property="errors", type="object", example={"id": {"The id is required."}}),
     *     )
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Internal Server Error",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Server Error"),
     *       @OA\Property(property="errors", type="object", example={"errors": {"Internal server error!"}}),
     *     )
     *   ),
     * 
     *  @OA\Response(
     *     response=404,
     *     description="Not Found",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="NotFound"),
     *       @OA\Property(property="errors", type="object", example={"errors": {"The book with the provided ID was not found."}}),
     *     )
     *   ),
     * )
     *
     * @param int $id Book ID to delete
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $book = Book::findOrFail($id);
            $book->delete();

            return response()->json([
                'message' => 'Successfully deleted',
                'book' => $book
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Book not found',
                'errors' => ['id' => 'The book with the provided ID was not found.']
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'errors' => ['errors' => 'Internal server error!']
            ], 500);
        }
    }
}
