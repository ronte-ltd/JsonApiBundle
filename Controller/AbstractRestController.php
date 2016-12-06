<?php

/*
 * This file is part of JsonApiBundle the package.
 *
 * (c) Alexey Astafev <efsneiron@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RonteLtd\JsonApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use RonteLtd\JsonApiBundle\Annotation\Links;
use RonteLtd\JsonApiBundle\Annotation\Meta;
use RonteLtd\JsonApiBundle\Serializer\Normalizer\Collection;

/**
 * AbstractRestController
 *
 * @author Alexey Astafev <efsneiron@gmail.com>
 */
abstract class AbstractRestController extends Controller
{
    /**
     * Render jsonapi from array or object
     * 
     * @param mixed $data
     * @return Response
     */
    protected function renderJsonApi($data)
    {
        if (!$data instanceof Collection) {
            $collection = new Collection($data);

            $refectionMethod = new \ReflectionMethod(static::class, debug_backtrace()[1]['function']);

            // Get links and meta
            $reader = $this->get('annotation_reader');
            $linksAnnotation = $reader->getMethodAnnotation($refectionMethod, "RonteLtd\JsonApiBundle\Annotation\Links");
            $metaAnnotation = $reader->getMethodAnnotation($refectionMethod, "RonteLtd\JsonApiBundle\Annotation\Meta");

            // Get JsonApi version
            $jsonapi = $this->container->getParameter('ronte_ltd_json_api.jsonapi');
            if (null !== $jsonapi) {
                $collection->setJsonapi($jsonapi);
            }

            // Get and set links
            $links = ($linksAnnotation instanceof Links) ? $linksAnnotation->getLinks() : [];
            $links['self'] = $this->get('request_stack')->getCurrentRequest()->getUri();
            $collection->setLinks($links);

            // Get and set JsonApi meta
            if ($metaAnnotation instanceof Meta) {
                $collection->setMeta($metaAnnotation->getMeta());
            }
        } else {
            $collection = &$data;
        }

        $jsonApi = $this->get('serializer')->serialize($collection, 'json');

        $response = new Response($jsonApi);
        $response->headers->set('Content-Type', 'application/vnd.api+json');

        return $response;
    }
}