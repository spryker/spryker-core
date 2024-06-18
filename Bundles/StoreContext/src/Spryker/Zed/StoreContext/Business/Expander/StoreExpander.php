<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business\Expander;

use Generated\Shared\Transfer\StoreCollectionTransfer;
use Spryker\Zed\StoreContext\Business\Reader\StoreContextReaderInterface;

class StoreExpander implements StoreExpanderInterface
{
    /**
     * @var \Spryker\Zed\StoreContext\Business\Reader\StoreContextReaderInterface
     */
    protected StoreContextReaderInterface $storeContextReader;

    /**
     * @param \Spryker\Zed\StoreContext\Business\Reader\StoreContextReaderInterface $storeContextReader
     */
    public function __construct(
        StoreContextReaderInterface $storeContextReader
    ) {
        $this->storeContextReader = $storeContextReader;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreCollectionTransfer $storeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\StoreCollectionTransfer
     */
    public function expandStoreCollectionTransferWithStoreContext(StoreCollectionTransfer $storeCollectionTransfer): StoreCollectionTransfer
    {
        $indexedStoreApplicationContextCollectionTransfer = $this->storeContextReader->getStoreApplicationContextCollectionsIndexedByIdStore($storeCollectionTransfer);

        /** @var \Generated\Shared\Transfer\StoreTransfer $storeTransfer */
        foreach ($storeCollectionTransfer->getStores() as $storeTransfer) {
            $storeTransfer->setApplicationContextCollection($indexedStoreApplicationContextCollectionTransfer[$storeTransfer->getIdStoreOrFail()] ?? null);
        }

        return $storeCollectionTransfer;
    }
}
