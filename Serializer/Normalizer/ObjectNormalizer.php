<?php
/*
 * This file is part of AppBundle the package.
 *
 * (c) Ruslan Muriev <muriev.r@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RonteLtd\JsonApiBundle\Serializer\Normalizer;

use RonteLtd\JsonApiBundle\Annotation\Attribute;
use RonteLtd\JsonApiBundle\Annotation\Relationship;
use RonteLtd\JsonApiBundle\Annotation\ObjectNormalizer as ObjectNormalizerAnnotation;
use RonteLtd\JsonApiBundle\Serializer\Mapping\AttributeMetadata;
use RonteLtd\JsonApiBundle\Serializer\Mapping\AttributeMetadataInterface;
use RonteLtd\JsonApiBundle\Serializer\Mapping\ClassAnnotationInterface;
use RonteLtd\JsonApiBundle\Serializer\Mapping\ClassMetadataInterface;
use RonteLtd\JsonApiBundle\Serializer\Mapping\Factory\MetadataFactoryInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer as BaseObjectNormalizer;

/**
 * Class JsonApiNormalizer
 *
 * @package RonteLtd\JsonApiBundle\Serializer\Normalizer
 * @author Ruslan Muriev <muriev.r@gmail.com>
 */
class ObjectNormalizer extends BaseObjectNormalizer
{
    /**
     * @var MetadataFactoryInterface
     */
    protected $metadataFactory;

    /**
     * ObjectNormalizer constructor.
     *
     * @param MetadataFactoryInterface $metadataFactory
     * @param ClassMetadataFactoryInterface|null $classMetadataFactory
     * @param NameConverterInterface|null $nameConverter
     * @param PropertyAccessorInterface|null $propertyAccessor
     * @param PropertyTypeExtractorInterface|null $propertyTypeExtractor
     */
    public function __construct(MetadataFactoryInterface $metadataFactory, ClassMetadataFactoryInterface $classMetadataFactory = null, NameConverterInterface $nameConverter = null, PropertyAccessorInterface $propertyAccessor = null, PropertyTypeExtractorInterface $propertyTypeExtractor = null)
    {
        parent::__construct($classMetadataFactory, $nameConverter, $propertyAccessor, $propertyTypeExtractor);

        $this->metadataFactory = $metadataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        if (parent::supportsNormalization($data) === false) {
            return false;
        }

        return (boolean) $this->getNormalizerClassAnnotation(
            $this->metadataFactory->getMetadataFor($data)
        );
    }

    /**
     * {@inheritdoc}
     *
     * @throws CircularReferenceException
     */
    public function normalize($object, $format = null, array $context = array())
    {
        $this->setCircularReferenceHandler(function ($object) {
            return [];
        });

        $data = parent::normalize($object, $format, $context);

        if (!is_array($data)) {
            return $data;
        }

        // Get all metadata
        $classMetadata = $this->metadataFactory->getMetadataFor($object);
        $attributesMetadata = $classMetadata->getAttributesMetadata();
        $classAnnotation = $this->getNormalizerClassAnnotation($classMetadata);

        $jsonApi = [
            'type' => $classAnnotation->getName(),
            'id' => $object->getId(),
            'attributes' => [],
            'relationships' => []
        ];

        foreach ($data as $atrName => $atrVal) {
            $attrMetadataName = $this->nameConverter ? $this->nameConverter->denormalize($atrName) : $atrName;
            if (!array_key_exists($attrMetadataName, $attributesMetadata)) {
                continue;
            }

            $attributeMetadata = $attributesMetadata[$attrMetadataName];
            if (!$attributeMetadata instanceof AttributeMetadataInterface) {
                continue;
            }

            $attribute = $attributeMetadata->getAttribute();
            $relationship = $attributeMetadata->getRelationship();

            if ($attribute instanceof Attribute) {
                $name = $attribute->getName() ?: strtolower($atrName);

                $jsonApi['attributes'][$name] = $atrVal;
            }

            if ($relationship instanceof Relationship) {
                $name = $relationship->getName() ?: strtolower($atrName);

                $jsonApi['relationships'][$name]["data"] = $atrVal;
            }
        }

        return $jsonApi;
    }

    /**
     * @param ClassMetadataInterface $classMetadata
     * @return ObjectNormalizerAnnotation bool
     */
    protected function getNormalizerClassAnnotation(ClassMetadataInterface $classMetadata)
    {
        foreach ($classMetadata->getClassAnnotations() as $annotation) {
            if ($annotation instanceof ObjectNormalizerAnnotation) {
                return $annotation;
            }
        }

        return false;
    }
}