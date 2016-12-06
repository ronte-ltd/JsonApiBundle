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
 * Class Collection
 *
 * @package RonteLtd\JsonApiBundle\Serializer\Normalizer
 * @author Ruslan Muriev <muriev.r@gmail.com>
 */
class Collection extends ArrayCollection
{
    /**
     * JsonApi "jsonapi" example ["version" => "1.0"]
     *
     * @var array
     */
    protected $jsonapi = [];

    /**
     * JsonApi meta
     *
     * @var array
     */
    protected $meta = [];

    /**
     * JsonApi links
     *
     * @var array
     */
    protected $links = [];

    /**
     * Initializes a new ArrayCollection.
     *
     * @param $elements
     */
    public function __construct($elements = array())
    {
        if (!is_array($elements)) {
            $elements = [$elements];
        }

        parent::__construct($elements);
    }

    /**
     * @return array
     */
    public function getJsonapi()
    {
        return $this->jsonapi;
    }

    /**
     * @param array $jsonapi
     */
    public function setJsonapi($jsonapi)
    {
        $this->jsonapi = $jsonapi;
    }

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
    public function setMeta($meta)
    {
        $this->meta = $meta;
    }

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
    public function setLinks($links)
    {
        $this->links = $links;
    }
}