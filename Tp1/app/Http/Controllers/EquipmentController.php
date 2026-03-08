<?php

namespace App\Http\Controllers;

use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Models\Review;
use App\Models\Rental;
use Illuminate\Http\Request;
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
            $average = Review::whereIn('rental_id', $rentalIds)->avg('rating');

            
            return $average;
            
        } catch(Exception $ex){
            abort(500, 'Server error');
        }
    }

    /**
     * Display Equipement Location price
     */
    public function showLocationPrice(string $id)
    {
        try{

        } catch(Exception $ex){

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
        //
    }
}
