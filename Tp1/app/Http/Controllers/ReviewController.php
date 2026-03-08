<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReviewResource;
use Illuminate\Http\Request;
use App\Models\Review;
use Exception;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return (new ReviewResource(Review::findOrFail($id)))->response()->setStatusCode(200);
        } catch (ModelNotFoundException $ex) {
            abort(404, 'Invalid id');
        } catch (Exception $ex) {
            abort(500, 'Server error');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $review = Review::findOrFail($id);

            $review->delete();
            //NoContent source : https://stackoverflow.com/questions/49972284/laravel-how-to-response-only-204-code-status-with-no-body-message
            return response()->noContent();;
        } catch (ModelNotFoundException $ex) {
            abort(404, 'Invalid id');
        } catch (Exception $ex) {
            abort(500, 'Server error');
        }
    }
}
