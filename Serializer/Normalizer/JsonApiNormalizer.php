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

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\scalar;

/**
 * JsonApiNormalizer
 *
 * @author Alexey Astafev <efsneiron@gmail.com>
 */
class JsonApiNormalizer extends AbstractNormalizer
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @inheritdoc
     * @param Reader $reader
     */
    public function __construct(ClassMetadataFactoryInterface $classMetadataFactory = null, NameConverterInterface $nameConverter = null, Reader $reader)
    {
        $this->reader = $reader;
        parent::__construct($classMetadataFactory, $nameConverter);
    }

    /**
     * Denormalizes data back into an object of the given class.
     *
     * @param mixed $data data to restore
     * @param string $class the expected class to instantiate
     * @param string $format format the given data was extracted from
     * @param array $context options available to the denormalizer
     *
     * @return object
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        // TODO: Implement denormalize() method.
    }

    /**
     * Checks whether the given class is supported for denormalization by this normalizer.
     *
     * @param mixed $data Data to denormalize from
     * @param string $type The class to which the data should be denormalized
     * @param string $format The format being deserialized from
     *
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        // TODO: Implement supportsDenormalization() method.
    }

    /**
     * Normalizes an object into a set of arrays/scalars.
     *
     * @param object $object object to normalize
     * @param string $format format the normalization result will be encoded as
     * @param array $context Context options for the normalizer
     *
     * @return array|scalar
     */
    public function normalize($object, $format = null, array $context = array())
    {
        $attributes = $this->getAllowedAttributes($object, $context);
        var_dump($attributes); die();
    }

    /**
     * Checks whether the given class is supported for normalization by this normalizer.
     *
     * @param mixed $data Data to normalize
     * @param string $format The format being (de-)serialized from or into
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        if (is_object($data)) {
            $classAnnotation = $this->reader->getClassAnnotation(
                new \ReflectionClass($data), 'RonteLtd\JsonApiBundle\Annotation\JsonApi');

            if ($classAnnotation) {
                return true;
            }
        }

        return true;
    }
}