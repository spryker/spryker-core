<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Persistence;

use Generated\Shared\Transfer\MerchantSalesOrderTransfer;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderPersistenceFactory getFactory()
 */
class MerchantSalesOrderEntityManager extends AbstractEntityManager implements MerchantSalesOrderEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantSalesOrderTransfer $merchantSalesOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSalesOrderTransfer
     */
    public function createMerchantSalesOrder(MerchantSalesOrderTransfer $merchantSalesOrderTransfer): MerchantSalesOrderTransfer
    {
        $merchantSalesOrderMapper = $this->getFactory()->createMerchantSalesOrderMapper();

        $merchantSalesOrderEntity = $merchantSalesOrderMapper->mapMerchantSalesOrderTransferToMerchantSalesOrderEntity(
            $merchantSalesOrderTransfer,
            new SpyMerchantSalesOrder()
        );

        $merchantSalesOrderEntity->save();

        return $merchantSalesOrderMapper->mapMerchantSalesOrderEntityToMerchantSalesOrderTransfer(
            $merchantSalesOrderEntity,
            $merchantSalesOrderTransfer
        );
    }
}
