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
class ClassMetadata implements ClassMetadataInterface
{
    /**
     * @var string
     *
     * @internal This property is public in order to reduce the size of the
     *           class' serialized representation. Do not access it. Use
     *           {@link getName()} instead.
     */
    public $name;

    /**
     * @var AttributeMetadataInterface[]
     *
     * @internal This property is public in order to reduce the size of the
     *           class' serialized representation. Do not access it. Use
     *           {@link getAttributesMetadata()} instead.
     */
    public $attributesMetadata = array();

    /**
     * @var \ReflectionClass
     */
    private $reflClass;

    /**
     * Constructs a metadata for the given class.
     *
     * @param string $class
     */
    public function __construct($class)
    {
        $this->name = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function addAttributeMetadata(AttributeMetadataInterface $attributeMetadata)
    {
        $this->attributesMetadata[$attributeMetadata->getName()] = $attributeMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributesMetadata()
    {
        return $this->attributesMetadata;
    }

    private $classAnnotations = [];

    /**
     * @param ClassAnnotationInterface $classAnnotation
     */
    public function addClassAnnotation(ClassAnnotationInterface $classAnnotation)
    {
        $annotationClass = new \ReflectionClass($classAnnotation);

        $this->classAnnotations[$annotationClass->getShortName()] = $classAnnotation;
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
    public function merge(ClassMetadataInterface $classMetadata)
    {
        foreach ($classMetadata->getClassAnnotations() as $classAnnotation) {
            if (isset($this->classAnnotations[$classAnnotation->getName()])) {
                $this->classAnnotations[$classAnnotation->getName()] = $classAnnotation;
            } else {
                $this->addClassAnnotation($classAnnotation);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getReflectionClass()
    {
        if (!$this->reflClass) {
            $this->reflClass = new \ReflectionClass($this->getName());
        }

        return $this->reflClass;
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