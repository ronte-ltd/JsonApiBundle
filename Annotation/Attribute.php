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

use Doctrine\Common\Annotations\Annotation;

/**
 * JsonApi
 *
 * @author Alexey Astafev <efsneiron@gmail.com>
 * @Annotation
 * @Annotation\Target("PROPERTY")
 */
class Attribute
{
    /**
     * @var string
     */
    public $name;

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