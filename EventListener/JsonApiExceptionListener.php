<?php

namespace RonteLtd\JsonApiBundle\EventListener;

use RonteLtd\JsonApiBundle\Exception\Form\JsonApiFormException;
use RonteLtd\JsonApiBundle\Exception\Validation\JsonApiValidationException;
use RonteLtd\JsonApiBundle\Http\JsonApiResponse;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * JsonApiExceptionListener
 *
 * @author Sergey Kodentsov <soulkoden@gmail.com>
 */
class JsonApiExceptionListener
{
    /**
     * @var bool
     */
    private $debug;

    /**
     * @param bool $debug
     */
    public function __construct($debug)
    {
        $this->debug = $debug;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     * @return void
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        /** @var Request $currentRequest */
        $currentRequest = $event->getRequest();

        if (!in_array(JsonApiResponse::$CONTENT_TYPE, $currentRequest->getAcceptableContentTypes())) {
            return;
        }

        /** @var \Throwable $error */
        $exception = $event->getException();

        if ($exception instanceof JsonApiValidationException) {
            /** @var JsonApiResponse $exceptionResponse */
            $exceptionResponse = $this->handleValidationException($exception);
        } else {
            /** @var JsonApiResponse $exceptionResponse */
            $exceptionResponse = $this->handleStandardException($exception);
        }

        $event->setResponse($exceptionResponse);
    }

    /**
     * @param JsonApiValidationException $exception
     * @return JsonApiResponse
     */
    private function handleValidationException(JsonApiValidationException $exception)
    {
        /** @var ConstraintViolationListInterface $validationErrors */
        $validationErrors = $exception->getErrors();

        /** @var array $errors */
        $errors = [];

        /** @var ConstraintViolation $validationError */
        foreach ($validationErrors as $validationError) {
            $errors[] = [
                'source' => [
                    'pointer' => '/data/attributes/' . $validationError->getPropertyPath(),
                ],
                'detail' => $validationError->getMessage(),
            ];
        }

        $response = new JsonApiResponse(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);

        return $response;
    }

    /**
     * @param \Throwable $exception
     * @return JsonApiResponse
     */
    private function handleStandardException(\Throwable $exception)
    {
        $errors = [];

        do {
            if ($exception instanceof HttpExceptionInterface) {
                $currentStatusCode = $exception->getStatusCode();
            } else {
                $currentStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            }

            $error = [
                'status' => $currentStatusCode,
                'title' => $this->createTitleForException($exception),
                'detail' => $exception->getMessage(),
            ];

            if ($this->debug) {
                $error['meta'] = [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ];
            }

            $errors[] = $error;
        } while (($exception = $exception->getPrevious()));

        $response = new JsonApiResponse(['errors' => $errors], $errors[0]['status']);

        return $response;
    }

    /**
     * @param \Throwable $exception
     * @return string
     */
    private function createTitleForException(\Throwable $exception)
    {
        $path = explode('\\', get_class($exception));

        return array_pop($path);
    }
}