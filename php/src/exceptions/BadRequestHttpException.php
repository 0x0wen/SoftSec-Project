<?php

namespace src\exceptions;

class BadRequestHttpException extends BaseHttpException
{
    public function __construct(string $message, ?array $fieldErrors = null)
    {
        parent::__construct(400, $message, $fieldErrors);
    }
}
