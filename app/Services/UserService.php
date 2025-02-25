<?php
namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{

    /**
     * @param UserRepositoryInterface $userRepository
     * @param ImageService $imageService
     */
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private ImageService $imageService
    ) {}

    /**
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllUsers(int $page, int $perPage): LengthAwarePaginator
    {
        return $this->userRepository->getAllPaginated($page, $perPage);
    }

    /**
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User
    {
        if (isset($data['photo'])) {
            $path = $this->imageService->saveUploadedImage($data['photo']);

            $data['photo_path'] = $path;
        }

        return $this->userRepository->create($data);
    }
}
