<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Payone\Dependency;

interface HashInterface
{

    /**
     * @param string $value
     *
     * @return string
     */
    public function hash($value);

}
