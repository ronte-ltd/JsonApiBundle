<?php
/*
 * This file is part of AppBundle the package.
 *
 * (c) Ruslan Muriev <muriev.r@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RonteLtd\JsonApiBundle\Tests\Mapping\Loader;

use Doctrine\Common\Annotations\AnnotationReader;
use RonteLtd\JsonApiBundle\Serializer\Mapping\ClassMetadata;
use RonteLtd\JsonApiBundle\Serializer\Mapping\Loader\AnnotationLoader;
use RonteLtd\JsonApiBundle\Tests\Mapping\TestClassMetadataFactory;

/**
 * Class AnnotationLoaderTest
 *
 * @package RonteLtd\JsonApiBundle\Tests\Mapping\Loader
 * @author Ruslan Muriev <muriev.r@gmail.com>
 */
class AnnotationLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AnnotationLoader
     */
    private $loader;

    protected function setUp()
    {
        $this->loader = new AnnotationLoader(new AnnotationReader());
    }

    public function testInterface()
    {
        $this->assertInstanceOf('RonteLtd\JsonApiBundle\Serializer\Mapping\Loader\LoaderInterface', $this->loader);
    }

    public function testLoadClassMetadataReturnsTrueIfSuccessful()
    {
        $classMetadata = new ClassMetadata('RonteLtd\JsonApiBundle\Tests\Fixtures\AttributeDummy');

        $this->assertTrue($this->loader->loadClassMetadata($classMetadata));
    }

    public function testLoadAttributePropertyAnnotation()
    {
        $classMetadata = new ClassMetadata('RonteLtd\JsonApiBundle\Tests\Fixtures\AttributeDummy');
        $this->loader->loadClassMetadata($classMetadata);

        $this->assertEquals(TestClassMetadataFactory::createAttributeClassMetadata(), $classMetadata);
    }

    public function testLoadRelationshipPropertyAnnotation()
    {
        $classMetadata = new ClassMetadata('RonteLtd\JsonApiBundle\Tests\Fixtures\RelationshipDummy');
        $this->loader->loadClassMetadata($classMetadata);

        $this->assertEquals(TestClassMetadataFactory::createRelationshipClassMetadata(), $classMetadata);
    }

    public function testObjectNormalizerClassAnnotation()
    {
        $classMetadata = new ClassMetadata('RonteLtd\JsonApiBundle\Tests\Fixtures\ObjectNormalizerDummy');
        $this->loader->loadClassMetadata($classMetadata);

        $this->assertEquals(TestClassMetadataFactory::createObjectNormalizerClassMetadata(), $classMetadata);
    }

    public function testLoadClassMetadataAndMerge()
    {
        $classMetadata = new ClassMetadata('RonteLtd\JsonApiBundle\Tests\Fixtures\AttributeDummy');
        $objectNormalizerClassMetadata = new ClassMetadata('RonteLtd\JsonApiBundle\Tests\Fixtures\ObjectNormalizerDummy');

        $this->loader->loadClassMetadata($classMetadata);
        $this->loader->loadClassMetadata($objectNormalizerClassMetadata);
        $classMetadata->merge($objectNormalizerClassMetadata);

        $this->assertEquals(TestClassMetadataFactory::createMergedClassMetadata(), $classMetadata);
    }
}