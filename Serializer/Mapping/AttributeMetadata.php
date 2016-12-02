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
 * Class AttributeMetadata
 *
 * @package RonteLtd\Bundle\Serializer\Mapping
 * @author Ruslan Muriev <muriev.r@gmail.com>
 */
class AttributeMetadata implements AttributeMetadataInterface
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
     * @var array
     */
    public $attribute;

    /**
     * @var array
     */
    public $relationship;

    /**
     * Constructs a metadata for the given attribute.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @param array $attribute
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    }

    /**
     * @return array
     */
    public function getRelationship()
    {
        return $this->relationship;
    }

    /**
     * @param array $relationship
     */
    public function setRelationship($relationship)
    {
        $this->relationship = $relationship;
    }

    /**
     * {@inheritdoc}
     */
    public function merge(AttributeMetadataInterface $attributeMetadata)
    {
        // Overwrite only if not defined
        if (null === $this->attribute) {
            $this->attribute = $attributeMetadata->getAttribute();
        }

        // Overwrite only if not defined
        if (null === $this->relationship) {
            $this->relationship = $attributeMetadata->getRelationship();
        }
    }

    /**
     * Returns the names of the properties that should be serialized.
     *
     * @return string[]
     */
    public function __sleep()
    {
        return array('name', 'attribute', 'relationship');
    }
}