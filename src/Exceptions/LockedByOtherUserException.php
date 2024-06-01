<?php

namespace LowerRockLabs\Lockable\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class LockedByOtherUserException extends Exception
{

    public function __construct(public string $model_type, public string $model_id, public string $user_id)
    {

    }

    public function report(): bool
    {
        return false;
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): Response|bool
    {
        return false;
    }

    /**
     * Get the exception's context information.
     *
     * @return array<string, mixed>
     */
    public function context(): array
    {
        return [
            'model_id' => $this->model_id,
            'model_type' => $this->model_type,
            'user_id' => $this->user_id,
        ];
    }
}
