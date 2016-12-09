<?php
/*
 * This file is part of AppBundle the package.
 *
 * (c) Ruslan Muriev <muriev.r@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RonteLtd\JsonApiBundle\Tests\Fixtures;

use RonteLtd\JsonApiBundle\Annotation\Attribute;
use RonteLtd\JsonApiBundle\Annotation\Relationship;
use RonteLtd\JsonApiBundle\Annotation\ObjectNormalizer;

/**
 * Class ObjectNormalizerDummy
 *
 * @package RonteLtd\JsonApiBundle\Tests\Fixtures
 * @author Ruslan Muriev <muriev.r@gmail.com>
 *
 * @ObjectNormalizer(name="jsonApiEntityType", meta={"copyright": "copyright", "authors": {"a"}})
 */
class ObjectNormalizerDummy
{
    /**
     * @Attribute(name="name")
     *
     * @var
     */
    private $name;

    /**
     * @Attribute(name="isEnabled")
     *
     * @var boolean
     */
    private $enabled;

    /**
     * @Relationship
     *
     * @var
     */
    private $category;
}