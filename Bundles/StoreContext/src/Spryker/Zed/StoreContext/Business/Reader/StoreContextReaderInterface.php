<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business\Reader;

use Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer;
use Generated\Shared\Transfer\StoreCollectionTransfer;

interface StoreContextReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreCollectionTransfer $storeCollectionTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer>
     */
    public function getStoreApplicationContextCollectionsIndexedByIdStore(StoreCollectionTransfer $storeCollectionTransfer): array;

    /**
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer
     */
    public function getStoreApplicationContextCollectionByIdStore(int $idStore): StoreApplicationContextCollectionTransfer;
}
