<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfiguration\Persistence;

use Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer;
use Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfiguration;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SalesProductConfiguration\Persistence\SalesProductConfigurationPersistenceFactory getFactory()
 */
class SalesProductConfigurationEntityManager extends AbstractEntityManager implements SalesProductConfigurationEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer
     *
     * @return void
     */
    public function saveSalesOrderItemConfiguration(SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer): void
    {
        $salesOrderItemConfigurationTransfer->requireIdSalesOrderItem();

        $salesOrderItemConfigurationEntity = $this->getFactory()
            ->createSalesOrderItemConfigurationMapper()
            ->mapSalesOrderItemConfigurationTransferToSalesOrderItemConfigurationEntity(
                $salesOrderItemConfigurationTransfer,
                new SpySalesOrderItemConfiguration()
            );

        $salesOrderItemConfigurationEntity->save();
    }
}
