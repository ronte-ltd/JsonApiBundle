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
interface ClassMetadataInterface
{
    /**
     * Returns the name of the backing PHP class.
     *
     * @return string The name of the backing class
     */
    public function getName();

    /**
     * @param ClassAnnotationInterface $classAnnotation
     */
    public function addClassAnnotation(ClassAnnotationInterface $classAnnotation);

    /**
     * @return array
     */
    public function getClassAnnotations();

    /**
     * Adds an {@link AttributeMetadataInterface}.
     *
     * @param AttributeMetadataInterface $attributeMetadata
     */
    public function addAttributeMetadata(AttributeMetadataInterface $attributeMetadata);

    /**
     * Gets the list of {@link AttributeMetadataInterface}.
     *
     * @return AttributeMetadataInterface[]
     */
    public function getAttributesMetadata();

    /**
     * Merges a {@link ClassMetadataInterface} in the current one.
     *
     * @param ClassMetadataInterface $classMetadata
     */
    public function merge(ClassMetadataInterface $classMetadata);

    /**
     * Returns a {@link \ReflectionClass} instance for this class.
     *
     * @return \ReflectionClass
     */
    public function getReflectionClass();
}