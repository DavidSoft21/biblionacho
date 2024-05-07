<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Cmixin\BusinessDay;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\Api\LendBooks\LendBookCreateRequest;
use App\Http\Requests\Api\LendBooks\LendBookUpdateRequest;
use Illuminate\Http\JsonResponse;
use App\Models\LendBook;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class LendBookController extends Controller
{

    /**
     * @OA\Schema(
     *     schema="LendBook",
     *     required={"identification ","isbn", "observations", "deadline", "returned", "user_id", "book_id"},
     *    @OA\Property(
     *       property="identification",
     *       type="string",
     *       description="identification of user",
     *       example="123456789"
     *  ),
     * 
     *     @OA\Property(
     *         property="isbn",
     *         type="string",
     *         description="ISBN of the lend book",
     *         example="yI47e2b87c"
     *     ),
     *     @OA\Property(
     *         property="observations",
     *         type="string",
     *         description="Observations of the lend book",
     *         example=""
     *     ),
     *     @OA\Property(
     *         property="deadline",
     *         type="string",
     *         format="date", 
     *         description="Deadline of the lend book",
     *         example="10-05-2024" 
     *     ),
     *     @OA\Property(
     *         property="returned",
     *         type="boolean",
     *         description="Returned of the lend book",
     *         example=false 
     *     ),
     *     @OA\Property(
     *         property="user_id",
     *         type="integer",
     *         description="User ID of the lend book",
     *         example=1 
     *     ),
     *     @OA\Property(
     *         property="book_id",
     *         type="integer",
     *         description="Book ID of the lend book",
     *         example=1 
     *     ),
     * ),
     * @OA\Get(
     *   path="/lendbooks/index",
     *   summary="Index Lend Books",
     *   description="Show all lend books",
     *   operationId="IndexLendBooks",
     *   tags={"LendBooks"},
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
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Ok 200"),
     *       @OA\Property(property="lendbooks", type="array", @OA\Items(ref="#/components/schemas/LendBook")), 
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
     * )
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $lendbooks = LendBook::all();

            return response()->json([
                'message' => 'Ok 200',
                'lendbooks' => $lendbooks
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'errors' => ['errors' => 'Internal server error!']
            ], 500);
        }
    }


    /**
     * Calculates the number of weekdays, weekends, and holidays between a given date range in Colombia, ensuring the start date is after the current date.
     *
     * @param Carbon\Carbon $startDate The start date of the range (inclusive). Must be after the current date.
     * @param Carbon\Carbon $endDate The end date of the range (inclusive).
     * @return array An array containing the following keys:
     *   - totalDays: The total number of days between the start and end date (inclusive).
     *   - weekendDays: The number of Saturdays and Sundays within the date range.
     *   - holidayCount: The number of holidays in Colombia within the date range.
     *   - workingDays: The number of weekdays (excluding weekends and holidays) within the date range.
     *
     * @throws Exception If the start date is after the end date or before the current date.
     */
    public function countHolidaysColombia(Carbon $startDate, Carbon $endDate): array
    {
        $now = Carbon::now()->format('d-m-Y');
        $startDate = $startDate->startOfDay()->format('d-m-Y');
        $endDate = $endDate->endOfDay()->format('d-m-Y');

        // Log::info(' Start Date: => ' . $startDate . " end Day => " . $endDate . " now => " . $now);
        // dd('hola');

        if (Carbon::parse($startDate)->gt(Carbon::parse($endDate))) {
            throw new Exception('Start date cannot be after end date.');
        }

        if (Carbon::parse($startDate)->lt(Carbon::parse($now))) {
            throw new Exception('Start date must be on or after the current date.');
        }

        $businessDay = new BusinessDay();
        $totalDays = Carbon::parse($endDate)->diffInDays(Carbon::parse($startDate)) + 1;

        $weekendDays = Carbon::parse($startDate)->diffInDaysFiltered(function (Carbon $date) {
            return $date->isWeekend();
        }, $endDate);

        $holidays = $businessDay->getHolidays($startDate, $endDate, 'Colombia');
        $holidayCount = count($holidays());

        $nonWorkingDays = $weekendDays + $holidayCount;
        $workingDays = $totalDays - $nonWorkingDays;

        return [
            'totalDays' => $totalDays,
            'weekendDays' => $weekendDays,
            'holidayCount' => $holidayCount,
            'workingDays' => $workingDays,
        ];
    }

    /**
     * @OA\Post(
     *   path="/lendbooks/store",
     *   summary="Create Lend Books",
     *   description="Create a lend book",
     *   operationId="CreateLendBook",
     *   tags={"LendBooks"},
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
     *     description="Lend Book data",
     *     @OA\JsonContent(
     *       required={"identification","isbn", "observations", "returned", "user_id", "book_id"},
     *      @OA\Property(property="identification", type="string", example="123456789"),
     *       @OA\Property(property="isbn", type="string", example="yI47e2b87c"),
     *       @OA\Property(property="observations", type="string", example=""),
     *       @OA\Property(property="returned", type="boolean",  example="false"),
     *       @OA\Property(property="user_id", type="integer",  example="1"),
     *       @OA\Property(property="book_id", type="integer",  example="1"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Successfully registered",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Successfully registered"),
     *       @OA\Property(property="lendbook", type="object", ref="#/components/schemas/LendBook"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validation Error",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Validation Error"),
     *       @OA\Property(property="errors", type="object", example={"title": {"The isbn field is required."}}),
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
     * @param LendBookCreateRequest $request
     * @return JsonResponse
     */
    public function store(LendBookCreateRequest $request): JsonResponse
    {
        try {

            $validatedData = $request->validated();
            $today = Carbon::now()->startOfDay();
            $user = User::find($validatedData['user_id']);
            $deadline = '';

            switch (true) {
                case $user->hasRole('admin'):
                    $deadline = $today->copy()->addDays(20);
                    break;
                case $user->hasRole('employee'):
                    $deadline = $today->copy()->addDays(8);
                    break;
                case $user->hasRole('affiliate'):
                    $deadline = $today->copy()->addDays(10);
                    break;
                case $user->hasRole('guest'):
                    $deadline = $today->copy()->addDays(7);
                    break;
                default:
                    $deadline = $today->copy()->addDays(7);
                    break;
            }

            $holidays = $this->countHolidaysColombia($today, $deadline);
            $nonWorkingDays = $holidays['holidayCount'] + $holidays['weekendDays'] - 1;
            $deadline->addDays($nonWorkingDays);

            while ($deadline->isWeekend()) {
                $deadline->addDay();
            }

            $lendbook = LendBook::create([
                'identification' => $validatedData['identification'],
                'isbn' => $validatedData['isbn'],
                'observations' => $validatedData['observations'],
                'deadline' => $deadline->format('Y-m-d'),
                'returned' => $validatedData['returned'],
                'user_id' => $validatedData['user_id'],
                'book_id' => $validatedData['book_id']
            ]);

            return response()->json([
                'message' => 'Successfully registered',
                'lendbook' => $lendbook
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }


    /**
     * @OA\Get(
     *   path="/lendbooks/show/{id}",
     *   summary="Show Lend Book",
     *   description="Show a lend of book by ID",
     *   operationId="ShowLendBook",
     *   tags={"LendBooks"},
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
     *     description="Lend of Book ID",
     *     @OA\Schema(
     *       type="integer"
     *    )
     *   ),
     *   @OA\Response(
     *     response=200, 
     *     description="OK",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Ok 200"),
     *       @OA\Property(property="lendbook", type="object", ref="#/components/schemas/LendBook"),
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
     *       @OA\Property(property="errors", type="object", example={"errors": {"The Lend of Book with the provided ID was not found."}}),
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
            $lendbook = LendBook::findOrFail($id);

            return response()->json([
                'message' => 'Ok 200',
                'lendbook' => $lendbook
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'NotFound',
                'errors' => ['id' => 'The Lend of Book with the provided ID was not found.']
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
     *   path="/lendbooks/update/{id}",
     *   summary="Update lend of book for admin",
     *   description="Update lend of books",
     *   operationId="UpdateLendBook",
     *   tags={"LendBooks"},
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
     *     description="Lend of Book ID",
     *     @OA\Schema(
     *       type="integer"
     *    )
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     description="Lend of Book update",
     *     @OA\JsonContent(
     *       required={"identification" ,"isbn", "observations", "returned", "user_id", "book_id"},
     *       @OA\Property(property="identification", type="string", example="123456789"),
     *       @OA\Property(property="isbn", type="string", example="yI47e2b87c"),
     *       @OA\Property(property="observations", type="string", example=""),
     *       @OA\Property(property="returned", type="boolean",  example="false"),
     *       @OA\Property(property="user_id", type="integer",  example="1"),
     *       @OA\Property(property="book_id", type="integer",  example="1"),
     *    )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Successfully updated",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Successfully updated"),
     *       @OA\Property(property="lendbook", type="object", ref="#/components/schemas/LendBook"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validation Error",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Validation Error"),
     *       @OA\Property(property="errors", type="object", example={"title": {"The isbn field is required."}}),
     *     )
     *   ),
     * 
     *   @OA\Response(
     *     response=404,
     *     description="Not Found",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Not Found"),
     *       @OA\Property(property="errors", type="object", example={"errors": {"The Lend of Book with the provided ID was not found."}}),
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
     * @param LendBookUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(LendBookUpdateRequest $request, int $id)
    {
        try {
            $validatedData = $request->validated();

            $lendbook = LendBook::find($id);

            $lendbook->update([
                'identification' => $validatedData['identification'],
                'isbn' => $validatedData['isbn'],
                'observations' => $validatedData['observations'],
                'deadline' => $validatedData['deadline'],
                'returned' => $validatedData['returned'],
                'user_id' => $validatedData['user_id'],
                'book_id' => $validatedData['book_id']
            ]);

            return response()->json([
                'message' => 'Successfully updated',
                'lendbook' => $lendbook
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
     *   path="/lendbooks/destroy/{id}",
     *   summary="Delete Lend Book",
     *   description="Delete a lend of Book by ID",
     *   operationId="LendBookDelete",
     *   tags={"LendBooks"},
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
     *     description="Lend of Book ID",
     *     @OA\Schema(
     *       type="integer"
     *    )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Successfully deleted",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Successfully deleted"),
     *       @OA\Property(property="lendbook", type="object", ref="#/components/schemas/LendBook"),
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
     * 
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
     * @param int $id Lend Book ID to delete
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $book = LendBook::findOrFail($id);
            $book->delete();

            return response()->json([
                'message' => 'Successfully deleted',
                'book' => $book
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Book not found',
                'errors' => ['id' => 'The Lend of book with the provided ID was not found.']
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'errors' => ['errors' => 'Internal server error!']
            ], 500);
        }
    }
}
