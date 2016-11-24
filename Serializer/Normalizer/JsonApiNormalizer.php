<?php

/*
 * This file is part of JsonApiBundle the package.
 *
 * (c) Alexey Astafev <efsneiron@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RonteLtd\JsonApiBundle\Serializer\Normalizer;

use RonteLtd\JsonApiBundle\Annotation\Attribute;
use RonteLtd\JsonApiBundle\Annotation\Normalize;
use RonteLtd\JsonApiBundle\Annotation\Relationship;
use RonteLtd\JsonApiBundle\Serializer\Mapping\AttributeMetadata;
use RonteLtd\JsonApiBundle\Serializer\Mapping\ClassMetadataInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class JsonApiNormalizer
 *
 * @package RonteLtd\JsonApiBundle\Serializer\Normalizer
 * @author Ruslan Muriev <muriev.r@gmail.com>
 */
class JsonApiNormalizer extends ObjectNormalizer
{
    /**
     * JsonApiNormalizer constructor.
     * @param ClassMetadataFactoryInterface|null $classMetadataFactory
     * @param NameConverterInterface|null $nameConverter
     * @param PropertyAccessorInterface|null $propertyAccessor
     * @param PropertyTypeExtractorInterface|null $propertyTypeExtractor
     */
    public function __construct(ClassMetadataFactoryInterface $classMetadataFactory = null, NameConverterInterface $nameConverter = null, PropertyAccessorInterface $propertyAccessor = null, PropertyTypeExtractorInterface $propertyTypeExtractor = null)
    {
        parent::__construct($classMetadataFactory, $nameConverter, $propertyAccessor, $propertyTypeExtractor);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        if (!(is_object($data) && !$data instanceof \Traversable)) {
            return false;
        }

        $metadata = $this->classMetadataFactory->getMetadataFor($data);

        if (!$metadata instanceof ClassMetadataInterface) {
            return false;
        }

        if (empty($metadata->getClassAnnotations())) {
            return false;
        }

        foreach ($metadata->getClassAnnotations() as $annotation) {
            if ($annotation instanceof Normalize) {
                return true;
            }
        }

        return false;
    }
}