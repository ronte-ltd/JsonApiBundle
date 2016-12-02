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
 * Interface AttributeMetadataInterface
 *
 * @package RonteLtd\Bundle\Serializer\Mapping
 * @author Ruslan Muriev <muriev.r@gmail.com>
 */
interface AttributeMetadataInterface
{
    /**
     * Gets the attribute name.
     *
     * @return string
     */
    public function getName();
    
    /**
     * Get  attribute annotation
     *
     * @return array
     */
    public function getAttribute();

    /**
     * Set  attribute annotation
     *
     * @param array $attribute
     */
    public function setAttribute($attribute);

    /**
     * Get  relationship annotation
     *
     * @return array
     */
    public function getRelationship();

    /**
     * Set  relationship annotation
     *
     * @param array $relationship
     */
    public function setRelationship($relationship);

    /**
     * Merges an {@see AttributeMetadataInterface} with in the current one.
     *
     * @param AttributeMetadataInterface $attributeMetadata
     */
    public function merge(AttributeMetadataInterface $attributeMetadata);
}