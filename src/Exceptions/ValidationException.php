<?php
/**
 * MIT License
 * 
 * Copyright (c) 2025 Francesco Picciati
 * 
 * This source code is licensed under the MIT license
 * found in the LICENSE file in the root directory of this source tree.
 */
declare(strict_types=1);

namespace Mambusrl\npsquare_php\Exceptions;

class ValidationException extends \Exception
{
    private array $validationErrors;
    private string $responseBody;

    public function __construct(string $message, array $validationErrors = [], string $responseBody = '', int $code = 422)
    {
        parent::__construct($message, $code);
        $this->validationErrors = $validationErrors;
        $this->responseBody = $responseBody;
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    public function getResponseBody(): string
    {
        return $this->responseBody;
    }

    public function getFormattedErrors(): string
    {
        $formatted = "Validation Errors:\n";
        foreach ($this->validationErrors as $error) {
            $location = is_array($error['loc']) ? implode(' -> ', $error['loc']) : $error['loc'];
            $formatted .= "• Field: {$location}\n";
            $formatted .= "  Message: {$error['msg']}\n";
            if (isset($error['type'])) {
                $formatted .= "  Type: {$error['type']}\n";
            }
            $formatted .= "\n";
        }
        return $formatted;
    }
}