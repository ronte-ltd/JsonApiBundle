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
     * Get  attribute annotation
     *
     * @return array
     */
    public function getAttribute();

    /**
     * Set  attribute annotation
     *
     * @param array $jsonApiAttribute
     */
    public function setAttribute($jsonApiAttribute);

    /**
     * Get  relationship annotation
     *
     * @return array
     */
    public function getRelationship();

    /**
     * Set  relationship annotation
     *
     * @param array $jsonApiRelationship
     */
    public function setRelationship($jsonApiRelationship);
}