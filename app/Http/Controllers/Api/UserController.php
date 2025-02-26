<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserGetRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * @param UserService $userService
     */
    public function __construct(
        private UserService $userService
    ) {}

    /**
     * @param UserGetRequest $request
     * @return JsonResponse
     */
    public function index(UserGetRequest $request): JsonResponse
    {
        $users = $this->userService->getAllUsers($request->page, $request->count);
        if(!sizeof($users)) {
            return response()->json([
                'success' => false,
                'message' => "Page not found",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'page' => $request->page,
            'total_pages' => $users->lastPage(),
            'total_users' => $users->total(),
            'count' => $request->count,
            'links' => [
                'next_url' => '',
                'prev_url' => '',
            ],
            'users' => UserResource::collection($users)
        ]);
    }

    /**
     * @param UserStoreRequest $request
     * @return JsonResponse
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->validated());

        return response()->json([
            'success' => true,
            'user_id' => $user->id,
            'message' => "New user successfully registered",
        ], 201);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'success' => true,
            'user' => new UserResource($user)
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function positions(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'positions' => $this->userService->getAllPositions()
        ]);
    }
}
