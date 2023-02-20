<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Store\Plugin\Expander;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Store\StoreConfig;

class StoreStoreReferenceExpander implements StoreExpanderInterface
{
    /**
     * @var \Spryker\Client\Store\StoreConfig
     */
    protected $storeConfig;

    /**
     * @param \Spryker\Client\Store\StoreConfig $storeConfig
     */
    public function __construct(StoreConfig $storeConfig)
    {
        $this->storeConfig = $storeConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function expand(StoreTransfer $storeTransfer): StoreTransfer
    {
        $storeReferenceMap = $this->storeConfig->getStoreNameReferenceMap();

        if (!empty($storeReferenceMap[$storeTransfer->getName()])) {
            $storeTransfer->setStoreReference($storeReferenceMap[$storeTransfer->getName()]);
        }

        return $storeTransfer;
    }
}
