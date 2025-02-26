<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

/**
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property int $position_id
 * @property UploadedFile $photo
 */
class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:60'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string', 'regex:/^\+380\d{9}$/'],
            'position_id' => ['required', 'integer', Rule::exists('positions', 'id')],
            'photo' => ['required', 'image', 'mimes:jpeg,jpg', 'max:2048'],
        ];
    }

    protected function passedValidation(): void
    {
        if (User::query()->where('email', $this->email)->orWhere('phone', $this->phone)->exists()) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'User with this phone or email already exist'
            ], 409));
        }

        if ($photo = $this->file('photo')) {
            $image = Image::make($photo->getPathname());
            if ($image->width() < 70 || $image->height() < 70) {
                throw new HttpResponseException(response()->json([
                    'success' => false,
                    'message' => 'Photo must be at least 70x70px'
                ], 422));
            }
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'fails' => $validator->errors(),
        ], 422));
    }
}
