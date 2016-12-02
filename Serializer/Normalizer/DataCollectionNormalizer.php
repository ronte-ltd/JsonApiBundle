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

use RonteLtd\JsonApiBundle\Serializer\Exception\LogicException;
use RonteLtd\JsonApiBundle\Serializer\Mapping\Factory\MetadataFactoryInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class DataCollectionNormalizer
 *
 * @package RonteLtd\JsonApiBundle\Serializer\Normalizer
 * @author Ruslan Muriev <muriev.r@gmail.com>
 */
class DataCollectionNormalizer extends AbstractNormalizer
{
    /**
     * ObjectNormalizer constructor.
     *
     * @param MetadataFactoryInterface $metadataFactory
     * @param ClassMetadataFactoryInterface|null $classMetadataFactory
     * @param NameConverterInterface|null $nameConverter
     */
    public function __construct(MetadataFactoryInterface $metadataFactory, ClassMetadataFactoryInterface $classMetadataFactory = null, NameConverterInterface $nameConverter = null)
    {
        parent::__construct($classMetadataFactory, $nameConverter);

        $this->metadataFactory = $metadataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return ($data instanceof DataCollection);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        $jsonApiData = [
            'links' => [],
            'meta' => [],
            'data' => [],
            'included' => []
        ];

        foreach ($object as $item) {
            if ($this->serializer instanceof NormalizerInterface) {
                $jsonApiData['data'][] = $this->serializer->normalize($item);
            }
        }

        return $jsonApiData;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        throw new LogicException(sprintf('Cannot denormalize with "%s".', \JsonSerializable::class));
    }
}