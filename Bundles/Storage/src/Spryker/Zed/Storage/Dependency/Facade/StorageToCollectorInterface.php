<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
