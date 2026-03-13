<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ReviewController extends Controller
{

    /**
     * Display the specified resource.
     */
    #[OA\Get(
        path: '/api/reviews/{id}',
        summary: 'Afficher une review',
        tags: ['Reviews'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Review ID',
                in: 'path',
                required: true,
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 404, description: 'Review non trouvé'),
        ]
    )]
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
     * Remove the specified resource from storage.
     */
    #[OA\Delete(
        path: '/api/reviews/{id}',
        summary: 'Delete a review',
        description: 'Deletes a review by ID',
        tags: ['Reviews'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Review ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Review deleted successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Review not found'
            ),
        ]
    )]
    public function destroy(string $id)
    {
        try {
            $review = Review::findOrFail($id);

            $review->delete();

            // NoContent source : https://stackoverflow.com/questions/49972284/laravel-how-to-response-only-204-code-status-with-no-body-message
            return response()->noContent();
        } catch (ModelNotFoundException $ex) {
            abort(404, 'Invalid id');
        } catch (Exception $ex) {
            abort(500, 'Server error');
        }
    }
}
