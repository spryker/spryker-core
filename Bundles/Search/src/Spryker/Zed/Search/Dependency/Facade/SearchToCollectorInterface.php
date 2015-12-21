<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Search\Dependency\Facade;

interface SearchToCollectorInterface
{

    /**
     * @param array $keys
     *
     * @return bool
     */
    public function deleteSearchTimestamps(array $keys = []);

}
