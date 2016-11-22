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
 * Class ClassMetadata
 *
 * @package RonteLtd\JsonApiBundle\Serializer\Mapping
 * @author Ruslan Muriev <muriev.r@gmail.com>
 */
class ClassMetadata extends \Symfony\Component\Serializer\Mapping\ClassMetadata implements ClassMetadataInterface
{
    private $classAnnotations=[];

    /**
     * @param ClassAnnotationInterface $classAnnotation
     */
    public function addClassAnnotation(ClassAnnotationInterface $classAnnotation)
    {
        $this->classAnnotations[$classAnnotation->getName()]=$classAnnotation;
    }

    /**
     * @return array
     */
    public function getClassAnnotations()
    {
        return $this->classAnnotations;
    }
}