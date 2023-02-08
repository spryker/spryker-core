<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUserGui\Communication\Table;

use Orm\Zed\Stock\Persistence\Map\SpyStockTableMap;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AssignedWarehouseTable extends AbstractWarehouseTable
{
    /**
     * @var bool
     */
    protected const IS_CHECKBOX_SET_BY_DEFAULT = true;

    /**
     * @var string
     */
    protected const URL_TEMPLATE_ASSIGNED_WAREHOUSE_TABLE = 'assigned-warehouse-table?%s=%s';

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = parent::configure($config);

        $config->setUrl(
            sprintf(
                static::URL_TEMPLATE_ASSIGNED_WAREHOUSE_TABLE,
                static::PARAM_USER_UUID,
                $this->userTransfer->getUuidOrFail(),
            ),
        );

        return $config;
    }

    /**
     * @return \Orm\Zed\Stock\Persistence\SpyStockQuery
     */
    protected function prepareQuery(): SpyStockQuery
    {
        return $this->stockQuery
            ->joinSpyWarehouseUserAssignment()
            ->useSpyWarehouseUserAssignmentQuery()
                ->filterByUserUuid($this->userTransfer->getUuidOrFail())
            ->endUse()
            ->withColumn(SpyStockTableMap::COL_ID_STOCK, AbstractWarehouseTable::COL_ID_WAREHOUSE)
            ->withColumn(SpyStockTableMap::COL_NAME, AbstractWarehouseTable::COL_NAME)
            ->withColumn(SpyStockTableMap::COL_IS_ACTIVE, AbstractWarehouseTable::COL_IS_ACTIVE);
    }

    /**
     * @return string
     */
    protected function getCheckboxHeaderName(): string
    {
        return 'Assigned';
    }
}
