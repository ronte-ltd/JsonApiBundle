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

use Doctrine\Common\Annotations\Reader;
use RonteLtd\JsonApiBundle\Annotation\Attribute;
use RonteLtd\JsonApiBundle\Annotation\Relationship;
use RonteLtd\JsonApiBundle\Serializer\Mapping\AttributeMetadata;
use RonteLtd\JsonApiBundle\Serializer\Mapping\ClassMetadataInterface;
use RonteLtd\JsonApiBundle\Serializer\Mapping\ClassAnnotationInterface;

/**
 * Class AnnotationLoader
 *
 * @package RonteLtd\JsonApiBundle\Serializer\Mapping\Loader
 * @author Ruslan Muriev <muriev.r@gmail.com>
 */
class AnnotationLoader implements LoaderInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public function loadClassMetadata(ClassMetadataInterface $classMetadata)
    {
        $reflectionClass = $classMetadata->getReflectionClass();
        $className = $reflectionClass->name;
        $loaded = false;

        // Load class annotations
        foreach ($this->reader->getClassAnnotations($reflectionClass) as $classAnnotation) {
            if ($classAnnotation instanceof ClassAnnotationInterface) {
                if (null === $classAnnotation->getName()) {
                    $classAnnotation->setName($reflectionClass->getShortName());
                }

                $classMetadata->addClassAnnotation($classAnnotation);
            }
        }

        // Load class attributes annotations
        $attributesMetadata = $classMetadata->getAttributesMetadata();
        foreach ($reflectionClass->getProperties() as $property) {
            if (!isset($attributesMetadata[$property->name])) {
                $attributesMetadata[$property->name] = new AttributeMetadata($property->name);
                $classMetadata->addAttributeMetadata($attributesMetadata[$property->name]);
            }

            if ($property->getDeclaringClass()->name === $className) {
                foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
                    if ($annotation instanceof Attribute) {
                        $attributesMetadata[$property->name]->setAttribute($annotation);
                    } elseif ($annotation instanceof Relationship) {
                        $attributesMetadata[$property->name]->setRelationship($annotation);
                    }

                    $loaded = true;
                }
            }
        }

        return $loaded;
    }
}