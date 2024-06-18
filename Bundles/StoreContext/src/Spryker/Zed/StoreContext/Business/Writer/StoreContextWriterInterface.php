<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business\Writer;

use Generated\Shared\Transfer\StoreContextCollectionRequestTransfer;
use Generated\Shared\Transfer\StoreContextCollectionResponseTransfer;

interface StoreContextWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\StoreContextCollectionResponseTransfer
     */
    public function createStoreContextCollection(
        StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
    ): StoreContextCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\StoreContextCollectionResponseTransfer
     */
    public function updateStoreContextCollection(
        StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
    ): StoreContextCollectionResponseTransfer;
}
