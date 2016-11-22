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
 * Interface ClassMetadataInterface
 *
 * @package RonteLtd\JsonApiBundle\Serializer\Mapping
 * @author Ruslan Muriev <muriev.r@gmail.com>
 */
interface ClassMetadataInterface extends \Symfony\Component\Serializer\Mapping\ClassMetadataInterface
{
    /**
     * @param ClassAnnotationInterface $classAnnotation
     */
    public function addClassAnnotation(ClassAnnotationInterface $classAnnotation);

    /**
     * @return array
     */
    public function getClassAnnotations();
}