<?php

namespace RonteLtd\JsonApiBundle\Model;

/**
 * @author Sergey Kodentsov <soulkoden@gmail.com>
 */
abstract class AbstractJsonApiObjectRequest
{
    /**
     * @param array $array
     *
     * @return void
     */
    public function mergeWithArray(array $array)
    {
        if (isset($array['data'])
            && isset($array['data']['type'])
            && isset($array['data']['attributes'])
        ) {
            $this->mergeWithJsonApiArray($array);
        } else {
            $this->mergeWithPlainArray($array);
        }
    }

    /**
     * @param array $array
     *
     * @return void
     */
    private function mergeWithJsonApiArray(array $array)
    {
        $this->mergeWithPlainArray($array['data']['attributes']);
    }

    /**
     * @param array $array
     *
     * @return void
     */
    private function mergeWithPlainArray(array $array)
    {
        foreach ($array as $key => $value) {
            $setter = 'set' . ucfirst($key{0}) . substr($key, 1);

            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }
}