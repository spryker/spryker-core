<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Storage\Dependency\Facade;

interface StorageToCollectorInterface
{

    /**
     * @param array $keys
     *
     * @return bool
     */
    public function deleteStorageTimestamps(array $keys = []);

}
