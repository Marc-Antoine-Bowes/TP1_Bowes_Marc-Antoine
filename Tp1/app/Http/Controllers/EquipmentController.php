<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowLocationPriceRequest;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Models\Rental;
use App\Models\Review;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use OpenApi\Attributes as OA;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: '/api/equipment',
        summary: 'Liste de tous les Users',
        description: 'Returns list of Users',
        tags: ['Users'],
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
    public function index()
    {
        try {
            return EquipmentResource::collection(Equipment::paginate(20))->response()->setStatusCode(200);
        } catch (Exception $ex) {
            abort(500, 'Server error');
        }
    }

    /**
     * Display the specified resource.
     */
    #[OA\Get(
        path: '/api/equipement/{id}',
        summary: 'Afficher un équipement',
        tags: ['Equipment'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Equipment ID',
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
            return (new EquipmentResource(Equipment::findOrFail($id)))->response()->setStatusCode(200);
        } catch (ModelNotFoundException $ex) {
            abort(404, 'Invalid id');
        } catch (Exception $ex) {
            abort(500, 'Server error');
        }
    }

    /**
     * Display Equipement Review
     */
    #[OA\Get(
        path: '/api/equipement/{id}/Review',
        summary: 'Affiche la moyenne des review équipement',
        tags: ['Equipment'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Equipment ID',
                in: 'path',
                required: true,
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 404, description: 'Review non trouvé'),
        ]
    )]
    public function showReview(string $id)
    {
        try {
            // https://laravel.com/docs/12.x/eloquent-collections#method-find-or-fail
            // Source pluck : https://laravel.com/docs/12.x/queries
            $rentalIds = Rental::where('equipment_id', $id)->pluck('id');

            // Source whereIn : https://stackoverflow.com/questions/22758819/laravel-wherein-or-wherein et : https://api.laravel.com/docs/11.x/Illuminate/Database/Query/Builder.html#method_orWhereIn
            $avgRating = Review::whereIn('rental_id', $rentalIds)->avg('rating');

            // Avec l'aide de l'ia pour le json() : promt "Comment je retournes mes données en json, je suis en laravel"
            return response()->json($avgRating, 200);
        } catch (Exception $ex) {
            abort(500, 'Server error');
        }
    }

    /**
     * Display Equipement Location price
     */
    #[OA\Get(
        path: '/api/equipments/{id}/location-price',
        summary: "Prix moyen de location d'un équipement",
        description: 'Retourne le prix moyen des locations pour un équipement dans une plage de dates optionnelle',
        tags: ['Rentals'],
        parameters: [

            new OA\Parameter(
                name: 'id',
                description: "ID de l'équipement",
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 5)
            ),

            new OA\Parameter(
                name: 'mindate',
                description: 'Date minimale de location',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date', example: '2024-01-01')
            ),

            new OA\Parameter(
                name: 'maxdate',
                description: 'Date maximale de location',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date', example: '2024-12-31')
            ),
        ],

        responses: [

            new OA\Response(
                response: 200,
                description: 'Prix moyen retourné',
                content: new OA\JsonContent(
                    type: 'number',
                    format: 'float',
                    example: 125.50
                )
            ),

            new OA\Response(
                response: 403,
                description: 'Erreur de date (mindate > maxdate)'
            ),

            new OA\Response(
                response: 500,
                description: 'Erreur serveur'
            ),
        ]
    )]
    public function showLocationPrice(ShowLocationPriceRequest $request, string $id)
    {
        try {
            // Récupération des dates : https://laravel.com/docs/12.x/requests#retrieving-input
            $mindate = $request->query('mindate');
            $maxdate = $request->query('maxdate');

            if ($mindate && $maxdate && $mindate > $maxdate) {
                abort(403, 'Erreur de date');
            }

            $rental = Rental::where('equipment_id', $id);

            // Avec l'aide de l'ia et de la docu : https://laravel.com/docs/12.x/queries
            if ($mindate) {
                $rental->where('start_date', '>=', $mindate);
            }

            // Avec l'aide de l'ia et de la docu : https://laravel.com/docs/12.x/queries
            if ($maxdate) {
                $rental->where('end_date', '<=', $maxdate);
            }

            $avgPrice = $rental->avg('total_price');

            return response()->json($avgPrice, 200);
        } catch (Exception $ex) {
            abort(500, 'Server error');
        }
    }
}
