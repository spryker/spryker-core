<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence;

use Generated\Shared\Transfer\MerchantStockTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Orm\Zed\MerchantStock\Persistence\SpyMerchantStock;
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
     * @return \Generated\Shared\Transfer\MerchantStockTransfer
     */
    public function createMerchantStock(MerchantTransfer $merchantTransfer, StockTransfer $stockTransfer): MerchantStockTransfer
    {
        $merchantStockEntity = new SpyMerchantStock();

        $merchantStockEntity->setFkMerchant($merchantTransfer->requireIdMerchant()->getIdMerchant())
            ->setFkStock($stockTransfer->requireIdStock()->getIdStock())
            ->setIsDefault(true);
        $merchantStockEntity->save();

        return $this->getFactory()
            ->createMerchantStockMapper()
            ->mapMerchantStockEntityToMerchantStockTransfer($merchantStockEntity, new MerchantStockTransfer());
    }
}
