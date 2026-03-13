<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: '/api/users',
        summary: 'Liste de tous les Users',
        description: 'Returns list of Users',
        tags: ['Users'],
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
    public function index()
    {
        try {
            return UserResource::collection(User::paginate(20))->response()->setStatusCode(200);
        } catch (Exception $ex) {
            abort(500, 'Server error');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: '/api/users',
        summary: 'Créer un utilisateur',
        tags: ['Users'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['first_name', 'last_name', 'email', 'phone'],
                properties: [
                    new OA\Property(property: 'first_name', type: 'string', example: 'Joe'),
                    new OA\Property(property: 'last_name', type: 'string', example: 'Louis'),
                    new OA\Property(property: 'email', type: 'string', example: 'joelouis@email.com'),
                    new OA\Property(property: 'phone', type: 'string', example: '555-555-2123'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Utilisateur créé'
            ),
            new OA\Response(
                response: 422,
                description: 'id invalide'
            ),
            new OA\Response(
                response: 500,
                description: 'Erreur serveur'
            ),
        ]
    )]
    public function store(StoreUserRequest $request)
    {
        try {
            $user = User::create($request->validated());

            return (new UserResource($user))->response()->setStatusCode(201);
        } catch (QueryException $ex) {
            abort(422, 'Cannot be created in database');
        } catch (Exception $ex) {
            abort(500, 'Server error');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Put(
        path: '/api/users/{id}',
        summary: 'Mettre à jour un utilisateur',
        description: "Met à jour les informations d'un utilisateur existant",
        tags: ['Users'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: "ID de l'utilisateur",
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'first_name', type: 'string', example: 'Jean'),
                    new OA\Property(property: 'last_name', type: 'string', example: 'Tremblay'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jean@email.com'),
                    new OA\Property(property: 'phone', type: 'string', example: '418-555-1234'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Utilisateur mis à jour avec succès'
            ),
            new OA\Response(
                response: 404,
                description: 'id non trouvé'
            ),
            new OA\Response(
                response: 500,
                description: 'Erreur serveur'
            ),
        ]
    )]
    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            // https://laravel.com/docs/12.x/requests + https://laravel.com/docs/12.x/eloquent#updates + recherche AI pour le update

            $user = User::findOrFail($id);

            $user->update($request->validated());

            return (new UserResource($user))->response()->setStatusCode(200);
        } catch (ModelNotFoundException $ex) {
            abort(404, 'Invalid id');
        } catch (Exception $ex) {
            abort(500, 'Server error');
        }
    }
}
