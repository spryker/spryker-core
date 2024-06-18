<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business\Reader;

use Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer;
use Generated\Shared\Transfer\StoreCollectionTransfer;
use Spryker\Zed\StoreContext\Persistence\StoreContextRepositoryInterface;

class StoreContextReader implements StoreContextReaderInterface
{
    /**
     * @var \Spryker\Zed\StoreContext\Persistence\StoreContextRepositoryInterface
     */
    protected StoreContextRepositoryInterface $storeContextRepository;

    /**
     * @param \Spryker\Zed\StoreContext\Persistence\StoreContextRepositoryInterface $storeContextRepository
     */
    public function __construct(StoreContextRepositoryInterface $storeContextRepository)
    {
        $this->storeContextRepository = $storeContextRepository;
    }

    /**
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer
     */
    public function getStoreApplicationContextCollectionByIdStore(int $idStore): StoreApplicationContextCollectionTransfer
    {
        return $this->storeContextRepository->findStoreApplicationContextCollectionByIdStore($idStore) ?? new StoreApplicationContextCollectionTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreCollectionTransfer $storeCollectionTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer>
     */
    public function getStoreApplicationContextCollectionsIndexedByIdStore(StoreCollectionTransfer $storeCollectionTransfer): array
    {
        return $this->storeContextRepository->getStoreApplicationContextCollectionsIndexedByIdStore(
            $this->extractStoreIdsFromStoreCollectionTransfer($storeCollectionTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreCollectionTransfer $storeCollectionTransfer
     *
     * @return array<int>
     */
    protected function extractStoreIdsFromStoreCollectionTransfer(StoreCollectionTransfer $storeCollectionTransfer): array
    {
        $storeIds = [];

        /**
         * @var \Generated\Shared\Transfer\StoreTransfer $storeTransfer
         */
        foreach ($storeCollectionTransfer->getStores() as $storeTransfer) {
            $storeIds[] = $storeTransfer->getIdStoreOrFail();
        }

        return $storeIds;
    }
}
