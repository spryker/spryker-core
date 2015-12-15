<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Library\Sanitize;

interface FilterInterface
{

    /**
     * @param array $array
     *
     * @return array
     */
    public function filter(array $array);

}
