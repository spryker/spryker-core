<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StoreContextStorage\Expander;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\StoreContextStorage\StoreContextStorageConfig;

class StoreContextExpander implements StoreContextExpanderInterface
{
    /**
     * @var \Spryker\Client\StoreContextStorage\StoreContextStorageConfig
     */
    protected StoreContextStorageConfig $config;

    /**
     * @param \Spryker\Client\StoreContextStorage\StoreContextStorageConfig $config
     */
    public function __construct(StoreContextStorageConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function expandStore(StoreTransfer $storeTransfer): StoreTransfer
    {
        if ($storeTransfer->getApplicationContextCollection() === null) {
            return $storeTransfer;
        }

        foreach ($storeTransfer->getApplicationContextCollectionOrFail()->getApplicationContexts() as $storeApplicationContextTransfer) {
            if ($storeApplicationContextTransfer->getApplication() === $this->config->getApplicationName()) {
                return $storeTransfer->setTimezone($storeApplicationContextTransfer->getTimezoneOrFail());
            }

            if ($storeApplicationContextTransfer->getApplication() === null) {
                $storeTransfer->setTimezone($storeApplicationContextTransfer->getTimezoneOrFail());
            }
        }

        return $storeTransfer;
    }
}
