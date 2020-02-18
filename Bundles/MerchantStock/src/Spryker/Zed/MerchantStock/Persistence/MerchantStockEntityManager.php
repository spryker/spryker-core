<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantStock\Persistence\MerchantStockPersistenceFactory getFactory()
 */
class MerchantStockEntityManager extends AbstractEntityManager implements MerchantStockEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return void
     */
    public function createMerchantStock(MerchantTransfer $merchantTransfer, StockTransfer $stockTransfer): void
    {
        $merchantTransfer->requireIdMerchant();
        $stockTransfer->requireIdStock();

        $this->getFactory()->createMerchantStockEntity()
            ->setFkMerchant($merchantTransfer->requireIdMerchant()->getIdMerchant())
            ->setFkStock($stockTransfer->requireIdStock()->getIdStock())
            ->setIsDefault(true)
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return void
     */
    public function deleteMerchantStock(MerchantTransfer $merchantTransfer, StockTransfer $stockTransfer): void
    {
        $merchantTransfer->requireIdMerchant();
        $stockTransfer->requireIdStock();

        $this->getFactory()->createMerchantStockQuery()
            ->filterByFkMerchant($merchantTransfer->requireIdMerchant()->getIdMerchant())
            ->filterByFkStock($stockTransfer->requireIdStock()->getIdStock())
            ->findOne()
            ->delete();
    }
}
