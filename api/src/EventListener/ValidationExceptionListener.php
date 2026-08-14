<?php

namespace App\EventListener;

use App\Exception\ValidationException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener(event: 'kernel.exception')]
class ValidationExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof ValidationException) {
            return;
        }

        $event->setResponse(new JsonResponse(
            [
                'message' => 'Ошибка валидации.',
                'errors' => $exception->getErrors(),
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY,
        ));
    }
}