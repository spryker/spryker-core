<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Persistence;

use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistoryQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantOms\MerchantOmsDependencyProvider;
use Spryker\Zed\MerchantOms\Persistence\Propel\Mapper\MerchantOmsMapper;
use Spryker\Zed\MerchantOms\Persistence\Propel\Mapper\StateMachineItemMapper;

/**
 * @method \Spryker\Zed\MerchantOms\MerchantOmsConfig getConfig()
 * @method \Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface getRepository()
 */
class MerchantOmsPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery
     */
    public function getMerchantSalesOrderItemPropelQuery(): SpyMerchantSalesOrderItemQuery
    {
        return $this->getProvidedDependency(MerchantOmsDependencyProvider::PROPEL_QUERY_MERCHANT_SALES_ORDER_ITEM);
    }

    /**
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistoryQuery
     */
    public function getStateMachineItemStateHistoryPropelQuery(): SpyStateMachineItemStateHistoryQuery
    {
        return $this->getProvidedDependency(MerchantOmsDependencyProvider::PROPEL_QUERY_STATE_MACHINE_ITEM_STATE_HISTORY);
    }

    /**
     * @return \Spryker\Zed\MerchantOms\Persistence\Propel\Mapper\StateMachineItemMapper
     */
    public function createStateMachineItemMapper(): StateMachineItemMapper
    {
        return new StateMachineItemMapper();
    }

    /**
     * @return \Spryker\Zed\MerchantOms\Persistence\Propel\Mapper\MerchantOmsMapper
     */
    public function createMerchantOmsMapper(): MerchantOmsMapper
    {
        return new MerchantOmsMapper();
    }
}
