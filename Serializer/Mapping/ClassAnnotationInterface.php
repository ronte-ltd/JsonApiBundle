<?php
/*
 * This file is part of AppBundle the package.
 *
 * (c) Ruslan Muriev <muriev.r@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RonteLtd\JsonApiBundle\Serializer\Mapping;


/**
 * Interface ClassAnnotationInterface
 *
 * @package RonteLtd\JsonApiBundle\Serializer\Mapping
 * @author Ruslan Muriev <muriev.r@gmail.com>
 */
interface ClassAnnotationInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName(string $name);
}