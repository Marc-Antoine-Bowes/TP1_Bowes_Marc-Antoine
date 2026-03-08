<?php

namespace App\Http\Controllers;

use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Models\Review;
use App\Models\Rental;
use Illuminate\Http\Request;
use App\Http\Requests\ShowLocationPriceRequest;
use Exception;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
    public function showReview(string $id)
    {
        try{
            //https://laravel.com/docs/12.x/eloquent-collections#method-find-or-fail
            //Source pluck : https://laravel.com/docs/12.x/queries
            $rentalIds = Rental::where('equipment_id', $id)->pluck('id');

            //Source whereIn : https://stackoverflow.com/questions/22758819/laravel-wherein-or-wherein et : https://api.laravel.com/docs/11.x/Illuminate/Database/Query/Builder.html#method_orWhereIn
            $avgRating = Review::whereIn('rental_id', $rentalIds)->avg('rating');
            
            //Avec l'aide de l'ia pour le json() : promt "Comment je retournes mes données en json, je suis en laravel"
            return response()->json($avgRating,200);
        } catch(Exception $ex){
            abort(500, 'Server error');
        }
    }

    /**
     * Display Equipement Location price
     */
    public function showLocationPrice(ShowLocationPriceRequest $request,string $id)
    {
        try{
            //Récupération des dates : https://laravel.com/docs/12.x/requests#retrieving-input
            $mindate = $request->query('mindate');
            $maxdate = $request->query('maxdate');

            if($mindate && $maxdate && $mindate > $maxdate){
                abort(403, 'Erreur de date');
            }

            $rental = Rental::where('equipment_id', $id);

            //Avec l'aide de l'ia et de la docu : https://laravel.com/docs/12.x/queries
            if($mindate){
                $rental->where('start_date', '>=', $mindate);
            }

            //Avec l'aide de l'ia et de la docu : https://laravel.com/docs/12.x/queries
            if($maxdate){
                $rental->where('end_date', '<=', $maxdate );
            }

            $avgPrice = $rental->avg('total_price');

            return response()->json($avgPrice,200);
        } catch(Exception $ex){
            abort(500, 'Server error');
        }
    }
}
