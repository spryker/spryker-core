<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantSalesOrderTransfer;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder;

class MerchantSalesOrderMapper
{
    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder $merchantSalesOrderEntity
     * @param \Generated\Shared\Transfer\MerchantSalesOrderTransfer $merchantSalesOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSalesOrderTransfer
     */
    public function mapMerchantSalesOrderEntityToMerchantSalesOrderTransfer(
        SpyMerchantSalesOrder $merchantSalesOrderEntity,
        MerchantSalesOrderTransfer $merchantSalesOrderTransfer
    ): MerchantSalesOrderTransfer {
        return $merchantSalesOrderTransfer->fromArray($merchantSalesOrderEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantSalesOrderTransfer $merchantSalesOrderTransfer
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder $merchantSalesOrderEntity
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder
     */
    public function mapMerchantSalesOrderTransferToMerchantSalesOrderEntity(
        MerchantSalesOrderTransfer $merchantSalesOrderTransfer,
        SpyMerchantSalesOrder $merchantSalesOrderEntity
    ): SpyMerchantSalesOrder {
        $merchantSalesOrderEntity->fromArray($merchantSalesOrderTransfer->modifiedToArray());

        return $merchantSalesOrderEntity;
    }
}
