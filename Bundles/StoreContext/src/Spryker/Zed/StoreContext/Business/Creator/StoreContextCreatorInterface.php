<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business\Creator;

use Generated\Shared\Transfer\StoreContextCollectionRequestTransfer;
use Generated\Shared\Transfer\StoreContextCollectionResponseTransfer;

interface StoreContextCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreContextCollectionRequestTransfer $storeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\StoreContextCollectionResponseTransfer
     */
    public function createStoreContextCollection(StoreContextCollectionRequestTransfer $storeCollectionRequestTransfer): StoreContextCollectionResponseTransfer;
}
