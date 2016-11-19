<?php

namespace RonteLtd\JsonApiBundle\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * JsonApiResponse
 *
 * @author Sergey Kodentsov <soulkoden@gmail.com>
 */
class JsonApiResponse extends JsonResponse
{
    public static $CONTENT_TYPE = 'application/vnd.api+json';

    /**
     * @param mixed $data
     * @param int $status
     * @param array $headers
     * @param bool $json
     */
    public function __construct($data = null, $status = Response::HTTP_OK, array $headers = [], $json = false)
    {
        $headers['Content-Type'] = static::$CONTENT_TYPE;

        parent::__construct($data, $status, $headers, $json);
    }
}