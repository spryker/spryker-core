<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Storage\Helper;

use Codeception\Module;

trait StorageHelperTrait
{
    /**
     * @return \SprykerTest\Client\Storage\Helper\StorageHelper
     */
    protected function getStorageHelper(): StorageHelper
    {
        /** @var \SprykerTest\Client\Storage\Helper\StorageHelper $storageHelper */
        $storageHelper = $this->getModule('\\' . StorageHelper::class);

        return $storageHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule(string $name): Module;
}
