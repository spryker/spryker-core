<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Processor\Builder;

use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\StoreStorageTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface StoresApiResponseBuilderInterface
{
    /**
     * @param string $currentLocale
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Generated\Shared\Transfer\StoreStorageTransfer|null $storeStorageTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createSingleResourceGlueResponseTransfer(
        string $currentLocale,
        GlueResponseTransfer $glueResponseTransfer,
        ?StoreStorageTransfer $storeStorageTransfer
    ): GlueResponseTransfer;

    /**
     * @param array<\Generated\Shared\Transfer\StoreStorageTransfer> $storeStorageTransfers
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function mapStoreStorageTransfersToCollectionResourceGlueResponseTransfer(
        array $storeStorageTransfers,
        GlueResponseTransfer $glueResponseTransfer
    ): GlueResponseTransfer;

    /**
     * @param array<\Generated\Shared\Transfer\StoreStorageTransfer> $storeStorageTransfers
     *
     * @return array<mixed>
     */
    public function mapStoreStorageTransfersToStoresArray(array $storeStorageTransfers): array;

    /**
     * @param string $currentLocale
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function create404GlueResponseTransfer(string $currentLocale): GlueResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\StoreStorageTransfer $storeStorageTransfer
     *
     * @return \Generated\Shared\Transfer\StoreStorageTransfer
     */
    public function mapStoreTransferToStoreStorageTransfer(
        StoreTransfer $storeTransfer,
        StoreStorageTransfer $storeStorageTransfer
    ): StoreStorageTransfer;
}
