<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfiguration\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer;
use Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfiguration;

class SalesOrderItemConfigurationMapper
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer
     * @param \Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfiguration $salesOrderItemConfigurationEntity
     *
     * @return \Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfiguration
     */
    public function mapSalesOrderItemConfigurationTransferToSalesOrderItemConfigurationEntity(
        SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer,
        SpySalesOrderItemConfiguration $salesOrderItemConfigurationEntity
    ): SpySalesOrderItemConfiguration {
        $salesOrderItemConfigurationEntity->fromArray($salesOrderItemConfigurationTransfer->modifiedToArray());
        $salesOrderItemConfigurationEntity->setFkSalesOrderItem($salesOrderItemConfigurationTransfer->getIdSalesOrderItem());

        return $salesOrderItemConfigurationEntity;
    }
}
