<?php

namespace RonteLtd\JsonApiBundle\Model;

/**
 * @author Sergey Kodentsov <soulkoden@gmail.com>
 */
interface JsonApiObjectResponseInterface
{
    public function getId();

    public function getAttributes();

    public function getRouteName();
}