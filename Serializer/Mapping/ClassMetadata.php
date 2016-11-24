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

use Symfony\Component\Serializer\Mapping\ClassMetadataInterface as BaseClassMetadataInterface;

/**
 * Class ClassMetadata
 *
 * @package RonteLtd\JsonApiBundle\Serializer\Mapping
 * @author Ruslan Muriev <muriev.r@gmail.com>
 */
class ClassMetadata extends \Symfony\Component\Serializer\Mapping\ClassMetadata implements ClassMetadataInterface
{
    private $classAnnotations = [];

    /**
     * @param ClassAnnotationInterface $classAnnotation
     */
    public function addClassAnnotation(ClassAnnotationInterface $classAnnotation)
    {
        $this->classAnnotations[$classAnnotation->getName()] = $classAnnotation;
    }

    /**
     * @return array
     */
    public function getClassAnnotations()
    {
        return $this->classAnnotations;
    }

    /**
     * {@inheritdoc}
     */
    public function merge(BaseClassMetadataInterface $classMetadata)
    {
        foreach ($classMetadata->getAttributesMetadata() as $attributeMetadata) {
            if (isset($this->attributesMetadata[$attributeMetadata->getName()])) {
                $this->attributesMetadata[$attributeMetadata->getName()]->merge($attributeMetadata);
            } else {
                $this->addAttributeMetadata($attributeMetadata);
            }
        }

        if ($classMetadata instanceof ClassMetadataInterface) {
            foreach ($classMetadata->getClassAnnotations() as $classAnnotation) {
                if (isset($this->classAnnotations[$classAnnotation->getName()])) {
                    $this->classAnnotations[$classAnnotation->getName()] = $attributeMetadata;
                } else {
                    $this->addClassAnnotation($classAnnotation);
                }
            }
        }
    }

    /**
     * Returns the names of the properties that should be serialized.
     *
     * @return string[]
     */
    public function __sleep()
    {
        return array(
            'name',
            'classAnnotations',
            'attributesMetadata',
        );
    }
}