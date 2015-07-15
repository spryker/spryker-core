<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Payone\Model;

use SprykerFeature\Shared\Payone\Dependency\HashInterface;

abstract class AbstractHashProvider implements HashInterface
{

    /**
     * @param string $value
     *
     * @return string
     */
    public function hash($value)
    {
        return hash('md5', $value);
    }

}
