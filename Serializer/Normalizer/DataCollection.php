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

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class DataCollection
 *
 * @package RonteLtd\JsonApiBundle\Serializer\Normalizer
 * @author Ruslan Muriev <muriev.r@gmail.com>
 */
class DataCollection extends ArrayCollection
{
    /**
     * Initializes a new ArrayCollection.
     *
     * @param array $elements
     */
    public function __construct(array $elements = array())
    {
        parent::__construct($elements);
    }
}