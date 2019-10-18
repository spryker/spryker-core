<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication\Table;

use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\DecimalObject\Decimal;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer;
use Spryker\Zed\AvailabilityGui\Communication\Helper\AvailabilityHelperInterface;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AvailabilityAbstractTable extends AbstractTable
{
    public const TABLE_COL_ACTION = 'Actions';
    public const URL_PARAM_ID_PRODUCT_ABSTRACT = 'id-product';
    public const AVAILABLE = 'Available';
    public const NOT_AVAILABLE = 'Not available';
    public const IS_BUNDLE_PRODUCT = 'Is bundle product';
    public const URL_PARAM_ID_STORE = 'id-store';

    /**
     * @var \Spryker\Zed\AvailabilityGui\Communication\Helper\AvailabilityHelperInterface
     */
    protected $availabilityHelper;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected $queryProductAbstractAvailability;

    /**
     * @var \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var int
     */
    protected $idStore;

    /**
     * @param \Spryker\Zed\AvailabilityGui\Communication\Helper\AvailabilityHelperInterface $availabilityHelper
     * @param \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     * @param int $idStore
     * @param int $idLocale
     */
    public function __construct(
        AvailabilityHelperInterface $availabilityHelper,
        AvailabilityToStoreFacadeInterface $storeFacade,
        int $idStore,
        int $idLocale
    ) {
        $this->availabilityHelper = $availabilityHelper;
        $this->storeFacade = $storeFacade;
        $this->idStore = $idStore;

        $this->queryProductAbstractAvailability = $this->availabilityHelper
            ->queryAvailabilityAbstractWithStockByIdLocale($idLocale, $idStore);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $url = Url::generate(
            '/availability-abstract-table',
            [
               static::URL_PARAM_ID_STORE => $this->idStore,
            ]
        );

        $config->setUrl($url);
        $config->setHeader([
            SpyProductAbstractTableMap::COL_SKU => 'SKU',
            AvailabilityHelperInterface::PRODUCT_NAME => 'Name',
            SpyAvailabilityAbstractTableMap::COL_QUANTITY => 'Availability',
            AvailabilityHelperInterface::STOCK_QUANTITY => 'Current Stock',
            AvailabilityHelperInterface::RESERVATION_QUANTITY => 'Reserved Products',
            static::IS_BUNDLE_PRODUCT => 'Is bundle product',
            AvailabilityHelperInterface::CONCRETE_NEVER_OUT_OF_STOCK_SET => 'Is never out of stock',
            static::TABLE_COL_ACTION => 'Actions',
        ]);

        $config->setSortable([
            SpyProductAbstractTableMap::COL_SKU,
            AvailabilityHelperInterface::PRODUCT_NAME,
            AvailabilityHelperInterface::STOCK_QUANTITY,
            AvailabilityHelperInterface::RESERVATION_QUANTITY,
        ]);

        $config->setSearchable([
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
        ]);

        $config->setDefaultSortColumnIndex(0);
        $config->addRawColumn(static::TABLE_COL_ACTION);
        $config->addRawColumn(SpyAvailabilityAbstractTableMap::COL_QUANTITY);
        $config->addRawColumn(static::IS_BUNDLE_PRODUCT);
        $config->addRawColumn(SpyProductAbstractTableMap::COL_SKU);
        $config->setDefaultSortDirection(TableConfiguration::SORT_DESC);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $result = [];

        $queryResult = $this->runQuery($this->queryProductAbstractAvailability, $config, true);

        foreach ($queryResult as $productAbstractEntity) {
            $haveBundledProducts = $this->haveBundledProducts($productAbstractEntity);

            $isNeverOutOfStock = $this->isNeverOutOfStock($productAbstractEntity);

            $result[] = [
                SpyProductAbstractTableMap::COL_SKU => $this->getProductEditPageLink($productAbstractEntity->getSku(), $productAbstractEntity->getIdProductAbstract()),
                AvailabilityQueryContainer::PRODUCT_NAME => $productAbstractEntity->getProductName(),
                SpyAvailabilityAbstractTableMap::COL_QUANTITY => $this->getAvailabilityLabel($productAbstractEntity, $isNeverOutOfStock),
                AvailabilityHelperInterface::STOCK_QUANTITY => $this->getStockQuantity($productAbstractEntity)->trim(),
                AvailabilityHelperInterface::RESERVATION_QUANTITY => ($haveBundledProducts) ? 'N/A' : $this->calculateReservation($productAbstractEntity)->trim(),
                static::IS_BUNDLE_PRODUCT => ($haveBundledProducts) ? 'Yes' : 'No',
                AvailabilityHelperInterface::CONCRETE_NEVER_OUT_OF_STOCK_SET => ($isNeverOutOfStock) ? 'Yes' : 'No',
                static::TABLE_COL_ACTION => $this->createViewButton($productAbstractEntity),
            ];
        }

        return $result;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return bool
     */
    protected function isNeverOutOfStock(SpyProductAbstract $productAbstractEntity): bool
    {
        return $this->availabilityHelper->isNeverOutOfStock(
            $productAbstractEntity->getVirtualColumn(AvailabilityHelperInterface::CONCRETE_NEVER_OUT_OF_STOCK_SET)
        );
    }

    /**
     * @param string $sku
     * @param int $idProductAbstract
     *
     * @return string
     */
    protected function getProductEditPageLink($sku, $idProductAbstract)
    {
        $pageEditUrl = Url::generate('/product-management/edit', [
            'id-product-abstract' => $idProductAbstract,
        ])->build();

        $pageEditLink = '<a target="_blank" href="' . $pageEditUrl . '">' . $sku . '</a>';

        return $pageEditLink;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     * @param bool $isNeverOutOfStock
     *
     * @return string
     */
    protected function getAvailabilityLabel(SpyProductAbstract $productAbstractEntity, bool $isNeverOutOfStock): string
    {
        if ((new Decimal($productAbstractEntity->getVirtualColumn(AvailabilityHelperInterface::AVAILABILITY_QUANTITY) ?? 0))->greaterThan(0) || $isNeverOutOfStock) {
            return $this->generateLabel(static::AVAILABLE, 'label-info');
        }

        return $this->generateLabel(static::NOT_AVAILABLE, '');
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function getStockQuantity(SpyProductAbstract $productAbstractEntity): Decimal
    {
        return (new Decimal($productAbstractEntity->getVirtualColumn(AvailabilityHelperInterface::STOCK_QUANTITY) ?? 0));
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function createViewButton(SpyProductAbstract $productAbstractEntity)
    {
        $viewTaxSetUrl = Url::generate(
            '/availability-gui/index/view',
            [
                static::URL_PARAM_ID_PRODUCT_ABSTRACT => $productAbstractEntity->getIdProductAbstract(),
                static::URL_PARAM_ID_STORE => $this->idStore,
            ]
        );

        return $this->generateViewButton($viewTaxSetUrl, 'View');
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return bool
     */
    protected function haveBundledProducts(SpyProductAbstract $productAbstractEntity)
    {
        foreach ($productAbstractEntity->getSpyProducts() as $productEntity) {
            if ($productEntity->getSpyProductBundlesRelatedByFkProduct()->count() > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function calculateReservation(SpyProductAbstract $productAbstractEntity): Decimal
    {
        return $this->availabilityHelper->calculateReservation(
            $productAbstractEntity->getVirtualColumn(AvailabilityHelperInterface::RESERVATION_QUANTITY),
            $this->storeFacade->getStoreById($this->idStore)
        );
    }
}
