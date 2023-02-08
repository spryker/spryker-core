<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUserGui\Communication\Table;

use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\WarehouseUserGui\Dependency\Service\WarehouseUserGuiToUtilEncodingServiceInterface;
use Spryker\Zed\WarehouseUserGui\Dependency\Service\WarehouseUserGuiToUtilSanitizeServiceInterface;

abstract class AbstractWarehouseTable extends AbstractTable
{
    /**
     * @var bool
     */
    protected const IS_CHECKBOX_SET_BY_DEFAULT = false;

    /**
     * @var string
     */
    protected const COL_NAME = 'name';

    /**
     * @var string
     */
    protected const COL_IS_ACTIVE = 'is_active';

    /**
     * @var string
     */
    protected const COL_CHECKBOX = 'checkbox';

    /**
     * @var string
     */
    protected const COL_ID_WAREHOUSE = 'id_warehouse';

    /**
     * @uses \Spryker\Zed\WarehouseUserGui\Communication\Controller\AssignWarehouseController::PARAM_USER_UUID
     *
     * @var string
     */
    protected const PARAM_USER_UUID = 'user-uuid';

    /**
     * @var string
     */
    protected const LABEL_TITLE_INACTIVE = 'Inactive';

    /**
     * @var string
     */
    protected const LABEL_TITLE_ACTIVE = 'Active';

    /**
     * @var string
     */
    protected const LABEL_CLASS_DANGER = 'label-danger';

    /**
     * @var string
     */
    protected const LABEL_CLASS_SUCCESS = 'label-success';

    /**
     * @var \Generated\Shared\Transfer\UserTransfer
     */
    protected UserTransfer $userTransfer;

    /**
     * @var \Orm\Zed\Stock\Persistence\SpyStockQuery
     */
    protected SpyStockQuery $stockQuery;

    /**
     * @var \Spryker\Zed\WarehouseUserGui\Dependency\Service\WarehouseUserGuiToUtilEncodingServiceInterface
     */
    protected WarehouseUserGuiToUtilEncodingServiceInterface $encodingService;

    /**
     * @var \Spryker\Zed\WarehouseUserGui\Dependency\Service\WarehouseUserGuiToUtilSanitizeServiceInterface
     */
    protected WarehouseUserGuiToUtilSanitizeServiceInterface $sanitizeService;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Orm\Zed\Stock\Persistence\SpyStockQuery $stockQuery
     * @param \Spryker\Zed\WarehouseUserGui\Dependency\Service\WarehouseUserGuiToUtilEncodingServiceInterface $encodingService
     * @param \Spryker\Zed\WarehouseUserGui\Dependency\Service\WarehouseUserGuiToUtilSanitizeServiceInterface $sanitizeService
     */
    public function __construct(
        UserTransfer $userTransfer,
        SpyStockQuery $stockQuery,
        WarehouseUserGuiToUtilEncodingServiceInterface $encodingService,
        WarehouseUserGuiToUtilSanitizeServiceInterface $sanitizeService
    ) {
        $this->userTransfer = $userTransfer;
        $this->stockQuery = $stockQuery;
        $this->encodingService = $encodingService;
        $this->sanitizeService = $sanitizeService;
    }

    /**
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    abstract protected function prepareQuery(): ModelCriteria;

    /**
     * @return string
     */
    abstract protected function getCheckboxHeaderName(): string;

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_ID_WAREHOUSE => 'Warehouse ID',
            static::COL_NAME => 'Name',
            static::COL_IS_ACTIVE => 'Status',
            static::COL_CHECKBOX => $this->getCheckboxHeaderName(),
        ]);

        $config->setSortable([
            static::COL_ID_WAREHOUSE,
            static::COL_NAME,
            static::COL_IS_ACTIVE,
        ]);

        $config->setDefaultSortField(static::COL_ID_WAREHOUSE);

        $config->setRawColumns([
            static::COL_IS_ACTIVE,
            static::COL_CHECKBOX,
        ]);

        $config->setSearchable([
            static::COL_NAME,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<int, array<string, mixed>>
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $query = $this->prepareQuery();
        $stockEntities = $this->runQuery($query, $config, true);

        $warehouseCollection = [];
        foreach ($stockEntities as $stockEntity) {
            $warehouseCollection[] = $this->generateItem($stockEntity);
        }

        return $warehouseCollection;
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStock $stockEntity
     *
     * @return array<string, mixed>
     */
    protected function generateItem(SpyStock $stockEntity): array
    {
        return [
            static::COL_ID_WAREHOUSE => $stockEntity->getIdStock(),
            static::COL_NAME => $stockEntity->getName(),
            static::COL_IS_ACTIVE => $this->getStatusLabel((bool)$stockEntity->getIsActive()),
            static::COL_CHECKBOX => $this->getCheckboxColumn($stockEntity),
        ];
    }

    /**
     * @param bool $isActive
     *
     * @return string
     */
    protected function getStatusLabel(bool $isActive): string
    {
        return $isActive ?
            $this->generateLabel(static::LABEL_TITLE_ACTIVE, static::LABEL_CLASS_SUCCESS) :
            $this->generateLabel(static::LABEL_TITLE_INACTIVE, static::LABEL_CLASS_DANGER);
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStock $stockEntity
     *
     * @return string
     */
    protected function getCheckboxColumn(SpyStock $stockEntity): string
    {
        /** @var string $encodedTableData */
        $encodedTableData = $this->encodingService->encodeJson([
            'idWarehouse' => $stockEntity->getIdStock(),
            'warehouseUuid' => $stockEntity->getUuid(),
            'name' => $stockEntity->getName(),
            'status' => $stockEntity->getIsActive(),
        ]);

        return sprintf(
            '<input class="%s" type="checkbox" name="warehouseUuid[]" value="%s" %s data-info="%s" />',
            'js-warehouse-checkbox',
            $stockEntity->getUuid(),
            static::IS_CHECKBOX_SET_BY_DEFAULT ? 'checked' : '',
            $this->sanitizeService->escapeHtml($encodedTableData),
        );
    }
}
