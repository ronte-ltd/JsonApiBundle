<?php

namespace RonteLtd\JsonApiBundle\EventListener;

use RonteLtd\JsonApiBundle\Exception\Form\JsonApiFormException;
use RonteLtd\JsonApiBundle\Http\JsonApiResponse;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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

        if ($exception instanceof JsonApiFormException) {
            /** @var JsonApiResponse $exceptionResponse */
            $exceptionResponse = $this->handleFormException($exception);
        } else {
            /** @var JsonApiResponse $exceptionResponse */
            $exceptionResponse = $this->handleStandardException($exception);
        }

        $event->setResponse($exceptionResponse);
    }

    /**
     * @param JsonApiFormException $exception
     * @return JsonApiResponse
     */
    private function handleFormException(JsonApiFormException $exception)
    {
        /** @var FormInterface $form */
        $form = $exception->getForm();

        /** @var array $errors */
        $errors = [];

        foreach ($form->getErrors() as $error) {
            $errors[] = [
                'source' => [
                    'pointer' => '',
                ],
                'detail' => $error->getMessage(),
            ];
        }

        foreach ($form->all() as $child) {
            if ($child->isValid()) {
                continue;
            }

            foreach ($child->getErrors() as $error) {
                $errors[] = [
                    'source' => [
                        'pointer' => '/data/attributes/' . $child->getName(),
                    ],
                    'detail' => $error->getMessage(),
                ];
            }
        }

        $response = new JsonApiResponse(['errors' => $errors], $exception->getStatusCode());

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
            switch (true) {
                /** @var HttpExceptionInterface $exception */
                case $exception instanceof HttpExceptionInterface:
                    $currentStatusCode = $exception->getStatusCode();

                    break;

                // ...

                default:
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