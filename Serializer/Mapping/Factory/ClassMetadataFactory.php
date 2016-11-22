<?php
/*
 * This file is part of AppBundle the package.
 *
 * (c) Ruslan Muriev <muriev.r@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RonteLtd\JsonApiBundle\Serializer\Mapping\Factory;

use Doctrine\Common\Cache\Cache;
use RonteLtd\JsonApiBundle\Serializer\Mapping\ClassMetadata;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use RonteLtd\JsonApiBundle\Serializer\Mapping\Loader\LoaderInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;


/**
 * Class ClassMetadataFactory
 *
 * @package RonteLtd\JsonApiBundle\Serializer\Mapping\Factory
 * @author Ruslan Muriev <muriev.r@gmail.com>
 */
class ClassMetadataFactory implements ClassMetadataFactoryInterface
{
    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var array
     */
    private $loadedClasses;

    /**
     * @param LoaderInterface $loader
     * @param Cache|null      $cache
     */
    public function __construct(LoaderInterface $loader, Cache $cache = null)
    {
        $this->loader = $loader;
        $this->cache = $cache;

        if (null !== $cache) {
            @trigger_error(sprintf('Passing a Doctrine Cache instance as 2nd parameter of the "%s" constructor is deprecated since version 3.1. This parameter will be removed in Symfony 4.0. Use the "%s" class instead.', __CLASS__, CacheClassMetadataFactory::class), E_USER_DEPRECATED);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataFor($value)
    {
        $class = $this->getClass($value);

        if (isset($this->loadedClasses[$class])) {
            return $this->loadedClasses[$class];
        }

        if ($this->cache && ($this->loadedClasses[$class] = $this->cache->fetch($class))) {
            return $this->loadedClasses[$class];
        }

        $classMetadata = new ClassMetadata($class);
        $this->loader->loadClassMetadata($classMetadata);

        $reflectionClass = $classMetadata->getReflectionClass();

        // Include metadata from the parent class
        if ($parent = $reflectionClass->getParentClass()) {
            $classMetadata->merge($this->getMetadataFor($parent->name));
        }

        // Include metadata from all implemented interfaces
        foreach ($reflectionClass->getInterfaces() as $interface) {
            $classMetadata->merge($this->getMetadataFor($interface->name));
        }

        if ($this->cache) {
            $this->cache->save($class, $classMetadata);
        }

        return $this->loadedClasses[$class] = $classMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function hasMetadataFor($value)
    {
        try {
            $this->getClass($value);

            return true;
        } catch (InvalidArgumentException $invalidArgumentException) {
            // Return false in case of exception
        }

        return false;
    }

    /**
     * Gets a class name for a given class or instance.
     *
     * @param mixed $value
     *
     * @return string
     *
     * @throws InvalidArgumentException If the class does not exists
     */
    private function getClass($value)
    {
        if (is_string($value)) {
            if (!class_exists($value) && !interface_exists($value)) {
                throw new InvalidArgumentException(sprintf('The class or interface "%s" does not exist.', $value));
            }

            return ltrim($value, '\\');
        }

        if (!is_object($value)) {
            throw new InvalidArgumentException(sprintf('Cannot create metadata for non-objects. Got: "%s"', gettype($value)));
        }

        return get_class($value);
    }
}