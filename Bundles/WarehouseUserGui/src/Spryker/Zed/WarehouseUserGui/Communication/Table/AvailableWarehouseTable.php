<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUserGui\Communication\Table;

use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\Stock\Persistence\Map\SpyStockTableMap;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Orm\Zed\WarehouseUser\Persistence\Map\SpyWarehouseUserAssignmentTableMap;
use Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignmentQuery;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\WarehouseUserGui\Dependency\Service\WarehouseUserGuiToUtilEncodingServiceInterface;
use Spryker\Zed\WarehouseUserGui\Dependency\Service\WarehouseUserGuiToUtilSanitizeServiceInterface;

class AvailableWarehouseTable extends AbstractWarehouseTable
{
    /**
     * @var string
     */
    protected const URL_TEMPLATE_AVAILABLE_WAREHOUSE_TABLE = 'available-warehouse-table?%s=%s';

    /**
     * @var \Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignmentQuery
     */
    protected SpyWarehouseUserAssignmentQuery $warehouseUserAssignmentQuery;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Orm\Zed\Stock\Persistence\SpyStockQuery $stockQuery
     * @param \Spryker\Zed\WarehouseUserGui\Dependency\Service\WarehouseUserGuiToUtilEncodingServiceInterface $encodingService
     * @param \Spryker\Zed\WarehouseUserGui\Dependency\Service\WarehouseUserGuiToUtilSanitizeServiceInterface $sanitizeService
     * @param \Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignmentQuery $warehouseUserAssignmentQuery
     */
    public function __construct(
        UserTransfer $userTransfer,
        SpyStockQuery $stockQuery,
        WarehouseUserGuiToUtilEncodingServiceInterface $encodingService,
        WarehouseUserGuiToUtilSanitizeServiceInterface $sanitizeService,
        SpyWarehouseUserAssignmentQuery $warehouseUserAssignmentQuery
    ) {
        parent::__construct($userTransfer, $stockQuery, $encodingService, $sanitizeService);

        $this->warehouseUserAssignmentQuery = $warehouseUserAssignmentQuery;
    }

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
                static::URL_TEMPLATE_AVAILABLE_WAREHOUSE_TABLE,
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
        $stockIds = $this->warehouseUserAssignmentQuery
            ->filterByUserUuid($this->userTransfer->getUuidOrFail())
            ->select([SpyWarehouseUserAssignmentTableMap::COL_FK_WAREHOUSE])
            ->find()
            ->getData();

        return $this->stockQuery
            ->filterByIdStock($stockIds, Criteria::NOT_IN)
            ->withColumn(SpyStockTableMap::COL_ID_STOCK, AbstractWarehouseTable::COL_ID_WAREHOUSE)
            ->withColumn(SpyStockTableMap::COL_NAME, AbstractWarehouseTable::COL_NAME)
            ->withColumn(SpyStockTableMap::COL_IS_ACTIVE, AbstractWarehouseTable::COL_IS_ACTIVE);
    }

    /**
     * @return string
     */
    protected function getCheckboxHeaderName(): string
    {
        return 'Assign';
    }
}
