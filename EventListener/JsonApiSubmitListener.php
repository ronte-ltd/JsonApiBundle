<?php

namespace RonteLtd\JsonApiBundle\EventListener;

use AppBundle\Model\DealRequest;
use Doctrine\Common\Annotations\Reader;
use RonteLtd\JsonApiBundle\Annotation\JsonApiObjectRequest;
use RonteLtd\JsonApiBundle\Http\JsonApiResponse;
use RonteLtd\JsonApiBundle\Model\JsonApiObjectRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * JsonApiSubmitListener
 *
 * @author Sergey Kodentsov <soulkoden@gmail.com>
 */
class JsonApiSubmitListener
{
    /**
     * @var Reader
     */
    private $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        /** @var callable $controller */
        $controller = $event->getController();

        /** @var Request $currentRequest */
        $currentRequest = $event->getRequest();

        if (!$controller
            || !$event->isMasterRequest()
            || 0 !== strpos($currentRequest->headers->get('Content-Type'), JsonApiResponse::$CONTENT_TYPE)
        ) {
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

        $reflectionController = new \ReflectionClass($controller[0]);
        $reflectionAction = $reflectionController->getMethod($controller[1]);

        /** @var JsonApiObjectRequest|null $jsonApiObjectRequest */
        $jsonApiObjectRequest = $this->reader->getMethodAnnotation(
            $reflectionAction,
            JsonApiObjectRequest::class
        );

        if (!$jsonApiObjectRequest) {
            return;
        }

        /** @var JsonApiObjectRequestInterface $objectRequestModel */
        $objectRequestModel = new $jsonApiObjectRequest->class;

        foreach ($submittedData['data']['attributes'] as $key => $value) {
            $setter = 'set' . ucfirst($key{0}) . substr($key, 1);

            if (method_exists($objectRequestModel, $setter)) {
                $objectRequestModel->$setter($value);
            }
        }

        $reflectionAction->invokeArgs($controller[0], [
            $objectRequestModel,
            $currentRequest,
        ]);
    }
}
