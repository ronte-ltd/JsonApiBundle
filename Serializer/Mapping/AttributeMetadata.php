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
use Symfony\Component\Serializer\Mapping\AttributeMetadataInterface;

/**
 * Class AttributeMetadata
 *
 * @package RonteLtd\Bundle\Serializer\Mapping
 * @author Ruslan Muriev <muriev.r@gmail.com>
 */
class AttributeMetadata extends \Symfony\Component\Serializer\Mapping\AttributeMetadata
{
    /**
     * @var array
     */
    public $attribute;

    /**
     * @var array
     */
    public $relationship;

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
        foreach ($attributeMetadata->getGroups() as $group) {
            $this->addGroup($group);
        }

        // Overwrite only if not defined
        if (null === $this->maxDepth) {
            $this->maxDepth = $attributeMetadata->getMaxDepth();
        }

        //Todo implement new interface
    }

    /**
     * Returns the names of the properties that should be serialized.
     *
     * @return string[]
     */
    public function __sleep()
    {
        return array('name', 'groups', 'maxDepth', 'attribute', 'relationship');
    }

}