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

/**
 * Class Meta
 *
 * @package RonteLtd\JsonApiBundle\Annotation
 * @author Ruslan Muriev <muriev.r@gmail.com>
 * @Annotation
 * @Annotation\Target("PROPERTY", "METHOD")
 */
class Meta
{
    /**
     * JsonApi metadata
     *
     * @var array
     */
    public $meta = [];

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param array $meta
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;
    }
}