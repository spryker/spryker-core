<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Payone\ClientApi\Request;

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
