<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Reader;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\Business\Exception\StoreReferenceNotFoundException;
use Spryker\Zed\Store\StoreConfig;

class StoreReferenceReader implements StoreReferenceReaderInterface
{
    /**
     * @var \Spryker\Zed\Store\StoreConfig
     */
    protected $storeConfig;

    /**
     * @param \Spryker\Zed\Store\StoreConfig $storeConfig
     */
    public function __construct(StoreConfig $storeConfig)
    {
        $this->storeConfig = $storeConfig;
    }

    /**
     * @param string $storeReference
     *
     * @throws \Spryker\Zed\Store\Business\Exception\StoreReferenceNotFoundException
     *
     * @return string
     */
    public function getStoreNameByStoreReference(string $storeReference): string
    {
        $storeReferenceMap = array_flip($this->storeConfig->getStoreNameReferenceMap());

        if (empty($storeReferenceMap[$storeReference])) {
            throw new StoreReferenceNotFoundException(
                sprintf(
                    'Could not get a store name by store-reference %s. Please check your configuration in %s::getStoreNameReferenceMap()',
                    $storeReference,
                    StoreConfig::class,
                ),
            );
        }

        return $storeReferenceMap[$storeReference];
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function extendStoreByStoreReference(StoreTransfer $storeTransfer): StoreTransfer
    {
        $storeReferenceMap = $this->storeConfig->getStoreNameReferenceMap();

        if (!empty($storeReferenceMap[$storeTransfer->getName()])) {
            $storeTransfer->setStoreReference($storeReferenceMap[$storeTransfer->getName()]);
        }

        return $storeTransfer;
    }
}
