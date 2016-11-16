<?php

/*
 * This file is part of ApiRestBundle the package.
 *
 * (c) Alexey Astafev <efsneiron@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RonteLtd\JsonApiBundle\Annotation;

/**
 * JsonApi
 *
 * @author Alexey Astafev <efsneiron@gmail.com>
 * @Annotation
 * @Target("PROPERTY")
 */
class Attribute
{
    /**
     * @var string
     */
    private $name;

    /**
     * Annotation constructor.
     *
     * @param array $values
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $values)
    {
        if(!array_key_exists('name', $values )){
            $this->name = null;
        }
//        var_dump($values);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }
}