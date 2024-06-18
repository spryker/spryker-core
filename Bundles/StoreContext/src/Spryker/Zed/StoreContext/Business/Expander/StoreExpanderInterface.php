<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business\Expander;

use Generated\Shared\Transfer\StoreCollectionTransfer;

interface StoreExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreCollectionTransfer $storeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\StoreCollectionTransfer
     */
    public function expandStoreCollectionTransferWithStoreContext(StoreCollectionTransfer $storeCollectionTransfer): StoreCollectionTransfer;
}
