<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Library\Filter;

interface FilterInterface
{

    /**
     * @param string $string
     *
     * @return string
     */
    public function filter($string);

}
