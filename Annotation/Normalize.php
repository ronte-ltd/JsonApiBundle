<?php
/*
 * This file is part of AppBundle the package.
 *
 * (c) Ruslan Muriev <muriev.r@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RonteLtd\JsonApiBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;
use RonteLtd\JsonApiBundle\Serializer\Mapping\ClassAnnotationInterface;

/**
 * Class Normalize
 *
 * @package RonteLtd\JsonApiBundle\Annotation
 * @author Ruslan Muriev <muriev.r@gmail.com>
 * @Annotation
 * @Annotation\Target("CLASS")
 */
class Normalize implements ClassAnnotationInterface
{
    /**
     * @var string
     */
    public $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }
}