<?php

namespace RonteLtd\JsonApiBundle\EventListener;

use RonteLtd\JsonApiBundle\Http\JsonApiResponse;
use RonteLtd\JsonApiBundle\Model\JsonApiObjectResponseInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Sergey Kodentsov <soulkoden@gmail.com>
 */
class JsonApiViewListener
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        /** @var Request $currentRequest */
        $currentRequest = $event->getRequest();

        if (!in_array(JsonApiResponse::$CONTENT_TYPE, $currentRequest->getAcceptableContentTypes())) {
            return;
        }

        /** @var mixed $result */
        $result = $event->getControllerResult();

        if (!$result instanceof JsonApiObjectResponseInterface
            && !(is_array($result) && $result[0] instanceof JsonApiObjectResponseInterface)
        ) {
            return;
        }

        $responseData = [
            'jsonapi' => [
                'version' => '1.0',
            ],
            'data' => [],
        ];

        $included = [];

        if (!is_array($result)) {
            $result = [$result];
        }

        $responseData['data'] = [];

        foreach ($result as $item) {
            $responseData['data'][] = $this->createSingleObject($item, $included);
        }

        if ($included) {
            $responseData['included'] = $included;
        }

        $response = new JsonApiResponse($responseData);

        $event->setResponse($response);
    }

    private function createSingleObject(JsonApiObjectResponseInterface $object, &$included = [])
    {
        $singleObject = [
            'type' => $this->createShortName($object),
            'id' => $object->getId(),
        ];

        if (true) {
            $attributes = [];
            $relationships = [];
            $links = [];

            foreach ($object->getAttributes() as $key => $value) {
                if (!$value instanceof JsonApiObjectResponseInterface) {
                    $attributes[$key] = (string)$value;
                } else {
                    $included[] = $this->createSingleObject($value, $included);

                    $relationships[$key]['data'][] = [
                        'type' => $this->createShortName($value),
                        'id' => $object->getId(),
                    ];
                }
            }

            if ($attributes) {
                $singleObject['attributes'] = $attributes;
            }

            if ($relationships) {
                $singleObject['relationships'] = $relationships;
            }

            /** @var string|null $routeName */
            $routeName = $object->getRouteName();

            if ($routeName) {
                $links['self'] = $this->router->generate(
                    $routeName,
                    [
                        'id' => $object->getId(),
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );
            }

            if ($links) {
                $singleObject['links'] = $links;
            }
        }

        return $singleObject;
    }

    private function createShortName(JsonApiObjectResponseInterface $object)
    {
        $parts = explode('\\', get_class($object));
        $shortName = array_pop($parts);
        $shortName{0} = strtolower($shortName{0});

        if (preg_match('/(?:ss|s|x|sh|ch|o)$/', $shortName)) {
            $shortName .= 'es';
        } elseif (preg_match('/[qwrtpsdfghjklzxcvbnm]y$/', $shortName)) {
            $shortName = substr($shortName, 0, strlen($shortName) - 1);
            $shortName .= 'ies';
        } elseif (preg_match('/(?:f|fe)$/', $shortName)) {
            $shortName = substr($shortName, 0, strlen($shortName) - 1);
            $shortName .= 'ves';
        } else {
            $shortName .= 's';
        }

        return $shortName;
    }
}