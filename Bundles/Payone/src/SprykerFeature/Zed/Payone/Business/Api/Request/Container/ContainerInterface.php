<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Business\Api\Request\Container;

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
