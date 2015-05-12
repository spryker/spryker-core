<?php

namespace SprykerFeature\Sdk\Payone\ClientApi\Request;


interface ContainerInterface
{

    /**
     * @return array
     */
    public function toArray();

    /**
     * @return string
     */
    public function __toString();

}
