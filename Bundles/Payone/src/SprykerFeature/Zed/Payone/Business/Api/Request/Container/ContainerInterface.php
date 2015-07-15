<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container;

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
