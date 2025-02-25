<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserGetRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\UserResource;
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
        $users = $this->userService->getAllUsers($request->page, $request->per_page);

        return response()->json(UserResource::collection($users));
    }

    /**
     * @param UserStoreRequest $request
     * @return JsonResponse
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->validated());

        return response()->json(new UserResource($user), 201);
    }
}
