<?php

namespace RonteLtd\JsonApiBundle\EventListener;

use RonteLtd\JsonApiBundle\Http\JsonApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * JsonApiSubmitListener
 *
 * @author Sergey Kodentsov <soulkoden@gmail.com>
 */
class JsonApiSubmitListener
{
    /**
     * @param GetResponseEvent $event
     * @return void
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        /** @var Request $currentRequest */
        $currentRequest = $event->getRequest();

        if (0 !== strpos($currentRequest->headers->get('Content-Type'), JsonApiResponse::$CONTENT_TYPE)) {
            return;
        }

        /** @var array|null $submittedData */
        $submittedData = json_decode($currentRequest->getContent(), true);

        if (json_last_error()) {
            throw new BadRequestHttpException(sprintf('JSON parse error: "%s".', json_last_error_msg()));
        }

        if (!isset($submittedData['data'])
            || !isset($submittedData['data']['type'])
            || !isset($submittedData['data']['attributes'])
        ) {
            throw new BadRequestHttpException('Is not a valid JSON API data.');
        }

        $postData = [
            $submittedData['data']['type'] => $submittedData['data']['attributes'],
        ];

        $currentRequest->server->set('Content-Type', 'application/x-www-form-urlencoded');

        $currentRequest->initialize(
            $currentRequest->query->all(),
            $postData,
            $currentRequest->attributes->all(),
            $currentRequest->cookies->all(),
            $currentRequest->files->all(),
            $currentRequest->server->all(),
            http_build_query($postData)
        );
    }
}