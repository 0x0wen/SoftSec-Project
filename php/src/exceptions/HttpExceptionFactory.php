<?php

namespace src\exceptions;

class HttpExceptionFactory
{
    static function createBadRequest(string $message, ?array $fieldErrors = null): BadRequestHttpException
    {
        return new BadRequestHttpException($message, $fieldErrors);
    }

    static function createUnauthorized(string $message): UnauthorizedHttpException
    {
        return new UnauthorizedHttpException($message);
    }

    static function createForbidden(string $message): ForbiddenHttpException
    {
        return new ForbiddenHttpException($message);
    }

    static function createNotFound(string $message): NotFoundHttpException
    {
        return new NotFoundHttpException($message);
    }

    static function createConflict(string $message): ConflictHttpException
    {
        return new ConflictHttpException($message);
    }

    static function createInternalServerError(string $message): InternalServerErrorHttpException
    {
        return new InternalServerErrorHttpException($message);
    }

    static function createFromBase(int $code, string $message): BaseHttpException
    {
        return new BaseHttpException($code, $message);
    }
}
