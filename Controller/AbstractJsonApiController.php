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

use RonteLtd\JsonApiBundle\Http\JsonApiResponse;
use RonteLtd\JsonApiBundle\Model\JsonApiResourceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * AbstractJsonApiController
 *
 * @author Alexey Astafev <efsneiron@gmail.com>
 * @author Sergey Kodentsov <soulkoden@gmail.com>
 */
abstract class AbstractJsonApiController extends Controller
{
    /**
     * Returns a JsonApiResponse.
     *
     * @param mixed $data
     * @param int $status
     * @param array $headers
     *
     * @return JsonApiResponse
     */
    protected function jsonapi($data, $status = Response::HTTP_OK, $headers = [])
    {
        if ((is_object($data) && $data instanceof JsonApiResourceInterface)
            || (is_array($data) && !empty($data) && $data[0] instanceof JsonApiResourceInterface)
        ) {
            // $data = serialize($data)...
        }

        return new JsonApiResponse($data, $status, $headers, false);
    }
}