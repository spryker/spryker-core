<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication\Table;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToOmsFacadeInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AvailabilityAbstractTable extends AbstractTable
{
    public const TABLE_COL_ACTION = 'Actions';
    public const URL_PARAM_ID_PRODUCT_ABSTRACT = 'id-product';
    public const AVAILABLE = 'Available';
    public const NOT_AVAILABLE = 'Not available';
    public const IS_BUNDLE_PRODUCT = 'Is bundle product';
    public const IS_NEVER_OUT_OF_STOCK = 'isNeverOutOfStock';
    public const URL_PARAM_ID_STORE = 'id-store';

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected $queryProductAbstractAvailability;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected $storeTransfer;

    /**
     * @var \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToOmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $queryProductAbstractAvailabilityGui
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToOmsFacadeInterface $omsFacade
     */
    public function __construct(
        SpyProductAbstractQuery $queryProductAbstractAvailabilityGui,
        StoreTransfer $storeTransfer,
        AvailabilityGuiToOmsFacadeInterface $omsFacade
    ) {
        $this->queryProductAbstractAvailability = $queryProductAbstractAvailabilityGui;
        $this->storeTransfer = $storeTransfer;
        $this->omsFacade = $omsFacade;
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
               static::URL_PARAM_ID_STORE => $this->storeTransfer->getIdStore(),
            ]
        );

        $config->setUrl($url);
        $config->setHeader([
            SpyProductAbstractTableMap::COL_SKU => 'SKU',
            AvailabilityQueryContainer::PRODUCT_NAME => 'Name',
            SpyAvailabilityAbstractTableMap::COL_QUANTITY => 'Availability',
            AvailabilityQueryContainer::STOCK_QUANTITY => 'Current Stock',
            AvailabilityQueryContainer::RESERVATION_QUANTITY => 'Reserved Products',
            static::IS_BUNDLE_PRODUCT => 'Is bundle product',
            AvailabilityQueryContainer::CONCRETE_NEVER_OUT_OF_STOCK_SET => 'Is never out of stock',
            static::TABLE_COL_ACTION => 'Actions',
        ]);

        $config->setSortable([
            SpyProductAbstractTableMap::COL_SKU,
            AvailabilityQueryContainer::PRODUCT_NAME,
            AvailabilityQueryContainer::STOCK_QUANTITY,
            AvailabilityQueryContainer::RESERVATION_QUANTITY,
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
                SpyAvailabilityAbstractTableMap::COL_QUANTITY => $this->getAvailabilityLabel($productAbstractEntity->getAvailabilityQuantity(), $isNeverOutOfStock),
                AvailabilityQueryContainer::STOCK_QUANTITY => $productAbstractEntity->getStockQuantity(),
                AvailabilityQueryContainer::RESERVATION_QUANTITY => ($haveBundledProducts) ? 'N/A' : $this->calculateReservation($productAbstractEntity->getReservationQuantity()),
                static::IS_BUNDLE_PRODUCT => ($haveBundledProducts) ? 'Yes' : 'No',
                AvailabilityQueryContainer::CONCRETE_NEVER_OUT_OF_STOCK_SET => ($isNeverOutOfStock) ? 'Yes' : 'No',
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
        return strpos($productAbstractEntity->getConcreteNeverOutOfStockSet(), 'true') !== false;
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
     * @param int $quantity
     * @param bool $isNeverOutOfStock
     *
     * @return string
     */
    protected function getAvailabilityLabel($quantity, $isNeverOutOfStock)
    {
        if ($quantity > 0 || $isNeverOutOfStock) {
            return $this->generateLabel(static::AVAILABLE, 'label-info');
        }

        return $this->generateLabel(static::NOT_AVAILABLE, '');
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
                static::URL_PARAM_ID_STORE => $this->storeTransfer->getIdStore(),
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
     * @param string $reservationQuantity
     *
     * @return int
     */
    protected function calculateReservation($reservationQuantity)
    {
        $reservationItems = explode(',', $reservationQuantity);
        $reservationItems = array_unique($reservationItems);

        return $this->getReservationUniqueValue($reservationItems);
    }

    /**
     * @param array $reservationItems
     *
     * @return int
     */
    protected function getReservationUniqueValue($reservationItems)
    {
        $reservation = 0;
        foreach ($reservationItems as $item) {
            $itemParts = explode(':', $item);
            if (count($itemParts) !== 2) {
                continue;
            }

            [$sku, $quantity] = $itemParts;

            $reservation += (int)$quantity;
            $reservation += $this->omsFacade->getReservationsFromOtherStores($sku, $this->storeTransfer);
        }

        return $reservation;
    }
}
