<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence;

use Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer;

/**
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductPersistenceFactory getFactory()
 */
interface PriceProductEntityManagerInterface
{
    /**
     * @api
     *
     * @return void
     */
    public function deleteOrphanPriceProductStoreEntities(): void;

    /**
     * @param \Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer $spyPriceProductDefaultEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer
     */
    public function savePriceProductDefaultEntity(
        SpyPriceProductDefaultEntityTransfer $spyPriceProductDefaultEntityTransfer
    ): SpyPriceProductDefaultEntityTransfer;
}
