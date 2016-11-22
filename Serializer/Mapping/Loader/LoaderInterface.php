<?php
/*
 * This file is part of AppBundle the package.
 *
 * (c) Ruslan Muriev <muriev.r@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RonteLtd\JsonApiBundle\Serializer\Mapping\Loader;

use RonteLtd\JsonApiBundle\Serializer\Mapping\ClassMetadataInterface;

/**
 * Interface LoaderInterface
 *
 * @package RonteLtd\JsonApiBundle\Serializer\Mapping\Loader
 * @author Ruslan Muriev <muriev.r@gmail.com>
 */
interface LoaderInterface
{
    /**
     * Load class metadata.
     *
     * @param ClassMetadataInterface $classMetadata A metadata
     *
     * @return bool
     */
    public function loadClassMetadata(ClassMetadataInterface $classMetadata);
}