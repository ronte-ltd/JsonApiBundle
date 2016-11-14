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
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\scalar;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

/**
 * JsonApiNormalizer
 *
 * @author Alexey Astafev <efsneiron@gmail.com>
 */
class JsonApiNormalizer implements SerializerAwareInterface, NormalizerInterface
{
    use SerializerAwareTrait;

    /**
     * @var Reader
     */
    private $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
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
        $class = new \ReflectionClass($object);
        $properties = $class->getDefaultProperties();
        $result = [];
        
        foreach (array_keys($properties) as $p) {
            $method = "get" . ucfirst($p);

            if (method_exists($object, $method)) {
//                $group = $this->reader->getMethodAnnotation(new \ReflectionMethod($method))
                $result[$p] = $object->$method();
            }
        }

        return [
            'data' => $result
        ];
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

        return false;
    }
}