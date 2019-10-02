<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockGui\Communication\Table;

use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\StockGui\Dependency\Facade\StockGuiToStockFacadeInterface;

class StockTable extends AbstractTable
{
    public const COL_ID_STOCK = 'id_stock';
    public const COL_NAME = 'name';
    public const COL_IS_ACTIVE = 'is_active';
    public const COL_AVAILABLE_IN_STORE = 'available_in_store';
    public const COL_ACTIONS = 'actions';

    public const IDENTIFIER = 'stock_data_table';

    /**
     * @var \Orm\Zed\Stock\Persistence\SpyStockQuery
     */
    protected $stockQuery;

    /**
     * @var \Spryker\Zed\StockGui\Dependency\Facade\StockGuiToStockFacadeInterface
     */
    protected $stockFacade;

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStockQuery $stockQuery
     * @param \Spryker\Zed\StockGui\Dependency\Facade\StockGuiToStockFacadeInterface $stockFacade
     */
    public function __construct(SpyStockQuery $stockQuery, StockGuiToStockFacadeInterface $stockFacade)
    {
        $this->stockQuery = $stockQuery;
        $this->stockFacade = $stockFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_ID_STOCK => 'Warehouse ID',
            static::COL_NAME => 'Name',
            static::COL_IS_ACTIVE => 'Status',
            static::COL_AVAILABLE_IN_STORE => 'Available in store',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setRawColumns([
            static::COL_IS_ACTIVE,
            static::COL_AVAILABLE_IN_STORE,
            static::COL_ACTIONS,
        ]);

        $config->setSearchable([
            static::COL_NAME,
        ]);
        $config->setSortable([
            static::COL_ID_STOCK,
            static::COL_NAME,
            static::COL_IS_ACTIVE,
        ]);
        $config->setDefaultSortField(static::COL_ID_STOCK);
        $this->setTableIdentifier(static::IDENTIFIER);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $stockToStoreMapping = $this->stockFacade->getWarehouseToStoreMapping();
        $queryResult = $this->runQuery($this->stockQuery, $config, true);

        $stockCollection = [];
        foreach ($queryResult as $stockEntity) {
            $stockCollection[] = $this->generateItem($stockEntity, $stockToStoreMapping);
        }

        return $stockCollection;
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStock $stockEntity
     * @param array $stockToStoreMapping
     *
     * @return array
     */
    protected function generateItem(SpyStock $stockEntity, array $stockToStoreMapping): array
    {
        return [
            static::COL_ID_STOCK => $stockEntity->getIdStock(),
            static::COL_NAME => $stockEntity->getName(),
            static::COL_IS_ACTIVE => $this->getStatusLabel((bool)$stockEntity->getIsActive()),
            static::COL_AVAILABLE_IN_STORE => $this->formatStoreNames($stockToStoreMapping[$stockEntity->getName()]),
            static::COL_ACTIONS => implode(' ', $this->createActionColumnButtons($stockEntity->getIdStock())),
        ];
    }

    /**
     * @param bool $isActive
     *
     * @return string
     */
    protected function getStatusLabel(bool $isActive): string
    {
        if (!$isActive) {
            return $this->generateLabel('Inactive', 'label-inactive');
        }

        return $this->generateLabel('Active', 'label-info');
    }

    /**
     * @param string[] $storeNames
     *
     * @return string
     */
    protected function formatStoreNames(array $storeNames): string
    {
        if (count($storeNames) === 0) {
            return '';
        }

        $storeNamesFormatted = [];
        foreach ($storeNames as $storeName) {
            $storeNamesFormatted[] = $this->generateLabel($storeName, 'label-info');
        }

        return implode(' ', $storeNamesFormatted);
    }

    /**
     * @param int $idStock
     *
     * @return string[]
     */
    protected function createActionColumnButtons(int $idStock): array
    {
        return [
            $this->generateStockViewButton($idStock),
            $this->generateStockEditButton($idStock),
        ];
    }

    /**
     * @param int $idStock
     *
     * @return string
     */
    protected function generateStockViewButton(int $idStock): string
    {
        return $this->generateViewButton(
            Url::generate('/stock-gui/view-stock', [
                'id-stock' => $idStock,
            ]),
            'View'
        );
    }

    /**
     * @param int $idStock
     *
     * @return string
     */
    protected function generateStockEditButton(int $idStock): string
    {
        return $this->generateEditButton(
            Url::generate('/stock-gui/edit-stock', [
                'id-stock' => $idStock,
            ]),
            'Edit'
        );
    }
}
