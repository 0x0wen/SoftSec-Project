<?php

namespace src\exceptions;

use Exception;

class BaseHttpException extends Exception
{
    // Store http status code in exception code
    // Store error message in exception message
    // Form field errors (key: field, value: error message) e.g. Array<{ field, message }>
    protected array | null $fieldErrors = null;


    public function __construct(int $code, string $message, ?array $fieldErrors = null)
    {
        parent::__construct($message, $code);
        $this->fieldErrors = $fieldErrors;
    }
}
