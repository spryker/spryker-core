<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business\Expander;

use Generated\Shared\Transfer\StoreCollectionTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\StoreContext\Business\Reader\StoreContextReaderInterface;
use Spryker\Zed\StoreContext\StoreContextConfig;

class StoreExpander implements StoreExpanderInterface
{
    /**
     * @param \Spryker\Zed\StoreContext\Business\Reader\StoreContextReaderInterface $storeContextReader
     * @param \Spryker\Zed\StoreContext\StoreContextConfig $storeContextConfig
     */
    public function __construct(
        protected StoreContextReaderInterface $storeContextReader,
        protected StoreContextConfig $storeContextConfig
    ) {
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

    /**
     * @param list<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return list<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expandStoreTransfersWithTimezone(array $storeTransfers): array
    {
        $expandedStoreTransfers = [];
        foreach ($storeTransfers as $storeTransfer) {
            $expandedStoreTransfers[] = $this->expandStoreWithTimezone($storeTransfer);
        }

        return $expandedStoreTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function expandStoreWithTimezone(StoreTransfer $storeTransfer): StoreTransfer
    {
        if ($storeTransfer->getApplicationContextCollection() === null) {
            return $storeTransfer;
        }

        foreach ($storeTransfer->getApplicationContextCollectionOrFail()->getApplicationContexts() as $storeApplicationContextTransfer) {
            if ($storeApplicationContextTransfer->getApplication() === $this->storeContextConfig->getApplicationName()) {
                return $storeTransfer->setTimezone($storeApplicationContextTransfer->getTimezoneOrFail());
            }

            if ($storeApplicationContextTransfer->getApplication() === null) {
                $storeTransfer->setTimezone($storeApplicationContextTransfer->getTimezoneOrFail());
            }
        }

        return $storeTransfer;
    }
}
