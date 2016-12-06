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
 * Class Links
 *
 * @package RonteLtd\JsonApiBundle\Annotation
 * @author Ruslan Muriev <muriev.r@gmail.com>
 * @Annotation
 * @Annotation\Target("PROPERTY", "METHOD")
 */
class Links
{
    /**
     * JsonApi links
     *
     * @var array
     */
    public $links = [];

    /**
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param array $links
     */
    public function setLinks(array $links)
    {
        $this->links = $links;
    }
}