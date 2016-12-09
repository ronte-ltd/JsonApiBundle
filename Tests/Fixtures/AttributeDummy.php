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

/**
 * Class AttributeDummy
 *
 * @package RonteLtd\JsonApiBundle\Tests\Fixtures
 * @author Ruslan Muriev <muriev.r@gmail.com>
 */
class AttributeDummy
{
    /**
     * @Attribute(name="a")
     *
     * @var
     */
    private $foo;

    /**
     * @Attribute
     *
     * @var
     */
    private $bar;

    /**
     * @var
     */
    private $fooBar;

    /**
     * @return mixed
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param mixed $foo
     */
    public function setFoo($foo)
    {
        $this->foo = $foo;
    }

    /**
     * @return mixed
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param mixed $bar
     */
    public function setBar($bar)
    {
        $this->bar = $bar;
    }

    /**
     * @return mixed
     */
    public function getFooBar()
    {
        return $this->fooBar;
    }

    /**
     * @param mixed $fooBar
     */
    public function setFooBar($fooBar)
    {
        $this->fooBar = $fooBar;
    }
}