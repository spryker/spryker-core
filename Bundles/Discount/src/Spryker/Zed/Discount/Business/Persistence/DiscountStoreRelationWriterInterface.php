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
     * @deprecated Use {@link \Spryker\Zed\Discount\Business\Creator\DiscountStoreCreatorInterface::createDiscountStoreRelationships()}
     *   or {@link \Spryker\Zed\Discount\Business\Updater\DiscountStoreUpdaterInterface::updateDiscountStoreRelationships()} instead.
     *
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    public function update(StoreRelationTransfer $storeRelationTransfer);
}
