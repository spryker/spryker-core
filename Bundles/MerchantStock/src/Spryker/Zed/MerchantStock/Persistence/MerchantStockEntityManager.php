<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence;

use Generated\Shared\Transfer\MerchantStockTransfer;
use Orm\Zed\MerchantStock\Persistence\SpyMerchantStock;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantStock\Persistence\MerchantStockPersistenceFactory getFactory()
 */
class MerchantStockEntityManager extends AbstractEntityManager implements MerchantStockEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantStockTransfer $merchantStockTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStockTransfer
     */
    public function createMerchantStock(MerchantStockTransfer $merchantStockTransfer): MerchantStockTransfer
    {
        $merchantStockTransfer->requireIdMerchant();
        $merchantStockTransfer->requireIdStock();

        $merchantStockMapper = $this->getFactory()->createMerchantStockMapper();
        $merchantStockEntity = $merchantStockMapper->mapMerchantStockTransferToMerchantStockEntity(
            $merchantStockTransfer,
            new SpyMerchantStock()
        );

        $merchantStockEntity->save();

        return $merchantStockMapper->mapMerchantStockEntityToMerchantStockTransfer(
            $merchantStockEntity,
            $merchantStockTransfer
        );
    }
}
