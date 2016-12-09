<?php
/*
 * This file is part of AppBundle the package.
 *
 * (c) Ruslan Muriev <muriev.r@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RonteLtd\JsonApiBundle\Tests\Mapping;

use RonteLtd\JsonApiBundle\Annotation\Attribute;
use RonteLtd\JsonApiBundle\Annotation\ObjectNormalizer;
use RonteLtd\JsonApiBundle\Annotation\Relationship;
use RonteLtd\JsonApiBundle\Serializer\Mapping\AttributeMetadata;
use RonteLtd\JsonApiBundle\Serializer\Mapping\ClassMetadata;


/**
 * Class TestClassMetadataFactory
 *
 * @package RonteLtd\JsonApiBundle\Tests\Mapping
 * @author Ruslan Muriev <muriev.r@gmail.com>
 */
class TestClassMetadataFactory
{
    public static function createAttributeClassMetadata()
    {
        $expected = new ClassMetadata('RonteLtd\JsonApiBundle\Tests\Fixtures\AttributeDummy');

        $fooAttributeAnnotation = new Attribute();
        $fooAttributeAnnotation->setName('a');

        $foo = new AttributeMetadata('foo');
        $foo->setAttribute($fooAttributeAnnotation);
        $expected->addAttributeMetadata($foo);

        $barAttributeAnnotation = new Attribute();

        $bar = new AttributeMetadata('bar');
        $bar->setAttribute($barAttributeAnnotation);
        $expected->addAttributeMetadata($bar);

        $fooBar = new AttributeMetadata('fooBar');
        $expected->addAttributeMetadata($fooBar);

        // load reflection class so that the comparison passes
        $expected->getReflectionClass();

        return $expected;
    }

    public static function createRelationshipClassMetadata()
    {
        $expected = new ClassMetadata('RonteLtd\JsonApiBundle\Tests\Fixtures\RelationshipDummy');

        $fooRelationshipAnnotation = new Relationship();
        $fooRelationshipAnnotation->setName('a');

        $foo = new AttributeMetadata('foo');
        $foo->setRelationship($fooRelationshipAnnotation);
        $expected->addAttributeMetadata($foo);

        $barRelationshipAnnotation = new Relationship();

        $bar = new AttributeMetadata('bar');
        $bar->setRelationship($barRelationshipAnnotation);
        $expected->addAttributeMetadata($bar);

        $fooBar = new AttributeMetadata('fooBar');
        $expected->addAttributeMetadata($fooBar);

        // load reflection class so that the comparison passes
        $expected->getReflectionClass();

        return $expected;
    }

    public static function createObjectNormalizerClassMetadata()
    {
        $expected = new ClassMetadata('RonteLtd\JsonApiBundle\Tests\Fixtures\ObjectNormalizerDummy');

        $objectNormalizer = new ObjectNormalizer();
        $objectNormalizer->setName("jsonApiEntityType");
        $objectNormalizer->setMeta([
            'copyright' => 'copyright',
            'authors'   => ['a']
        ]);

        $expected->addClassAnnotation($objectNormalizer);

        $nameAttributeAnnotation = new Attribute();
        $nameAttributeAnnotation->setName("name");

        $name = new AttributeMetadata('name');
        $name->setAttribute($nameAttributeAnnotation);
        $expected->addAttributeMetadata($name);

        $enabledAnnotation = new Attribute();
        $enabledAnnotation->setName('isEnabled');

        $enabled = new AttributeMetadata('enabled');
        $enabled->setAttribute($enabledAnnotation);
        $expected->addAttributeMetadata($enabled);

        $categoryRelationshipAnnotation = new Relationship();

        $category = new AttributeMetadata('category');
        $category->setRelationship($categoryRelationshipAnnotation);
        $expected->addAttributeMetadata($category);

        // load reflection class so that the comparison passes
        $expected->getReflectionClass();

        return $expected;
    }

    public static function createMergedClassMetadata()
    {
        $expected = self::createAttributeClassMetadata();

        $objectNormalizer = new ObjectNormalizer();
        $objectNormalizer->setName("jsonApiEntityType");
        $objectNormalizer->setMeta([
            'copyright' => 'copyright',
            'authors'   => ['a']
        ]);

        $expected->addClassAnnotation($objectNormalizer);

        $nameAttributeAnnotation = new Attribute();
        $nameAttributeAnnotation->setName("name");

        $name = new AttributeMetadata('name');
        $name->setAttribute($nameAttributeAnnotation);
        $expected->addAttributeMetadata($name);

        $enabledAnnotation = new Attribute();
        $enabledAnnotation->setName('isEnabled');

        $enabled = new AttributeMetadata('enabled');
        $enabled->setAttribute($enabledAnnotation);
        $expected->addAttributeMetadata($enabled);

        $categoryRelationshipAnnotation = new Relationship();

        $category = new AttributeMetadata('category');
        $category->setRelationship($categoryRelationshipAnnotation);
        $expected->addAttributeMetadata($category);

        // load reflection class so that the comparison passes
        $expected->getReflectionClass();

        return $expected;
    }
}