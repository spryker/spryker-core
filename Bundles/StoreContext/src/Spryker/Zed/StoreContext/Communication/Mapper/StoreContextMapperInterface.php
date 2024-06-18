<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Communication\Mapper;

use Generated\Shared\Transfer\StoreCollectionTransfer;
use Generated\Shared\Transfer\StoreContextCollectionRequestTransfer;
use Generated\Shared\Transfer\StoreContextCollectionResponseTransfer;
use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface StoreContextMapperInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return \Generated\Shared\Transfer\StoreCollectionTransfer
     */
    public function mapStoreTransfersToStoreCollectionTransfer(array $storeTransfers): StoreCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\StoreCollectionTransfer $storeCollectionTransfer
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function mapStoreCollectionTransferToStoreTransfers(StoreCollectionTransfer $storeCollectionTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreContextCollectionRequestTransfer
     */
    public function mapStoreTranferToStoreContextCollectionRequestTransfer(StoreTransfer $storeTransfer): StoreContextCollectionRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\StoreContextCollectionResponseTransfer $storeContextCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function mapStoreContextCollectionResponseTranferToStoreResponseTransfer(
        StoreContextCollectionResponseTransfer $storeContextCollectionResponseTransfer
    ): StoreResponseTransfer;
}
