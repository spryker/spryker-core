<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Store\Zed;

use Generated\Shared\Transfer\StoreCollectionTransfer;
use Generated\Shared\Transfer\StoreCriteriaTransfer;

interface StoreStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreCriteriaTransfer $storeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StoreCollectionTransfer
     */
    public function getStoreCollection(StoreCriteriaTransfer $storeCriteriaTransfer): StoreCollectionTransfer;
}
