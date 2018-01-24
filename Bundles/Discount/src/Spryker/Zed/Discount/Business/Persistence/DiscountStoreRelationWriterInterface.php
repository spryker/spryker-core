<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Persistence;

use Generated\Shared\Transfer\StoreRelationTransfer;

interface DiscountStoreRelationWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelation
     *
     * @return void
     */
    public function update(StoreRelationTransfer $storeRelation);
}
