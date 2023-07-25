<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Business\Reader;

use Generated\Shared\Transfer\StoreCriteriaTransfer;

interface StoreReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreCriteriaTransfer $storeCriteriaTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\StoreTransfer>
     */
    public function getStoreTransfersIndexedByIdStore(StoreCriteriaTransfer $storeCriteriaTransfer): array;
}
