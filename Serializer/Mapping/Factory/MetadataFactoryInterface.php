<?php
/*
 * This file is part of AppBundle the package.
 *
 * (c) Ruslan Muriev <muriev.r@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RonteLtd\JsonApiBundle\Serializer\Mapping\Factory;

use RonteLtd\JsonApiBundle\Serializer\Mapping\ClassMetadataInterface;
use RonteLtd\JsonApiBundle\Serializer\Exception\InvalidArgumentException;

/**
 * Interface MetadataFactoryInterface
 *
 * @package RonteLtd\JsonApiBundle\Serializer\Mapping\Factory
 * @author Ruslan Muriev <muriev.r@gmail.com>
 */
interface MetadataFactoryInterface
{
    /**
     * Get metadata
     *
     * @param string|object $value
     *
     * @return ClassMetadataInterface
     *
     * @throws InvalidArgumentException
     */
    public function getMetadataFor($value);

    /**
     * Checks if class has metadata.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function hasMetadataFor($value);
}