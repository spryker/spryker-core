<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Persistence;

use Generated\Shared\Transfer\StoreCriteriaTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface StoreRepositoryInterface
{
    /**
     * @param string $name
     *
     * @return bool
     */
    public function storeExists(string $name): bool;

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|null
     */
    public function findStoreByName(string $storeName): ?StoreTransfer;

    /**
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|null
     */
    public function findStoreById(int $idStore): ?StoreTransfer;

    /**
     * @param array<string> $storeNames
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function getStoreTransfersByStoreNames(array $storeNames): array;

    /**
     * @param \Generated\Shared\Transfer\StoreCriteriaTransfer $storeCriteriaTransfer
     *
     * @return array<string>
     */
    public function getStoreNamesByCriteria(StoreCriteriaTransfer $storeCriteriaTransfer): array;
}
