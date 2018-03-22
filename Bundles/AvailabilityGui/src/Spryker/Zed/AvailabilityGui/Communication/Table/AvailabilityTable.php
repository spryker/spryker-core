<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication\Table;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToOmsFacadeInterface;
use Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AvailabilityTable extends AbstractTable
{
    const TABLE_COL_ACTION = 'Actions';
    const URL_PARAM_ID_PRODUCT = 'id-product';
    const URL_PARAM_ID_PRODUCT_ABSTRACT = 'id-abstract';
    const URL_PARAM_SKU = 'sku';
    const URL_PARAM_ID_STORE = 'id-store';
    const URL_BACK_BUTTON = 'url-back-button';

    const IS_BUNDLE_PRODUCT = 'Is bundle product';

    /**
     * @var int
     */
    protected $idProductAbstract;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected $queryProductAbstractAvailability;

    /**
     * @var \Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerInterface
     */
    protected $productBundleQueryContainer;

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
     * @param int $idProductAbstract
     * @param \Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerInterface $productBundleQueryContainer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToOmsFacadeInterface $omsFacade
     */
    public function __construct(
        SpyProductAbstractQuery $queryProductAbstractAvailabilityGui,
        $idProductAbstract,
        AvailabilityGuiToProductBundleQueryContainerInterface $productBundleQueryContainer,
        StoreTransfer $storeTransfer,
        AvailabilityGuiToOmsFacadeInterface $omsFacade
    ) {

        $this->setTableIdentifier('availability-table');

        $this->queryProductAbstractAvailability = $queryProductAbstractAvailabilityGui;
        $this->idProductAbstract = $idProductAbstract;
        $this->productBundleQueryContainer = $productBundleQueryContainer;
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
        $url = Url::generate('availability-table', [
            AvailabilityAbstractTable::URL_PARAM_ID_PRODUCT_ABSTRACT => $this->idProductAbstract,
            static::URL_PARAM_ID_STORE => $this->storeTransfer->getIdStore(),
        ])->build();

        $config->setUrl($url);

        $config->setHeader([
            AvailabilityQueryContainer::CONCRETE_SKU => 'SKU',
            AvailabilityQueryContainer::CONCRETE_NAME => 'Name',
            AvailabilityQueryContainer::CONCRETE_AVAILABILITY => 'Availability',
            AvailabilityQueryContainer::STOCK_QUANTITY => 'Current Stock',
            AvailabilityQueryContainer::RESERVATION_QUANTITY => 'Reserved Products',
            static::IS_BUNDLE_PRODUCT => 'Is bundle product',
            AvailabilityQueryContainer::CONCRETE_NEVER_OUT_OF_STOCK_SET => 'Is never out of stock',
            static::TABLE_COL_ACTION => 'Actions',
        ]);

        $config->setSortable([
            AvailabilityQueryContainer::CONCRETE_SKU,
            AvailabilityQueryContainer::CONCRETE_NAME,
            AvailabilityQueryContainer::STOCK_QUANTITY,
            AvailabilityQueryContainer::RESERVATION_QUANTITY,
        ]);

        $config->setSearchable([
            SpyProductTableMap::COL_SKU,
            SpyProductLocalizedAttributesTableMap::COL_NAME,
        ]);

        $config->setDefaultSortColumnIndex(0);
        $config->addRawColumn(static::TABLE_COL_ACTION);
        $config->addRawColumn(static::IS_BUNDLE_PRODUCT);
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

        foreach ($queryResult as $productItem) {
            $isBundleProduct = $this->isBundleProduct($productItem[AvailabilityQueryContainer::ID_PRODUCT]);

            $isNeverOutOfStock = $this->isNeverOutOfStock(
                $productItem[AvailabilityQueryContainer::CONCRETE_NEVER_OUT_OF_STOCK_SET],
                $isBundleProduct
            );

            $result[] = [
                AvailabilityQueryContainer::CONCRETE_SKU => $productItem[AvailabilityQueryContainer::CONCRETE_SKU],
                AvailabilityQueryContainer::CONCRETE_NAME => $productItem[AvailabilityQueryContainer::CONCRETE_NAME],
                AvailabilityQueryContainer::CONCRETE_AVAILABILITY => $productItem[AvailabilityQueryContainer::CONCRETE_AVAILABILITY],
                AvailabilityQueryContainer::STOCK_QUANTITY => $productItem[AvailabilityQueryContainer::STOCK_QUANTITY],
                AvailabilityQueryContainer::RESERVATION_QUANTITY => ($isBundleProduct) ? 'N/A' : $this->calculateReservation($productItem),
                static::IS_BUNDLE_PRODUCT => ($isBundleProduct) ? 'Yes' : 'No',
                AvailabilityQueryContainer::CONCRETE_NEVER_OUT_OF_STOCK_SET => $isNeverOutOfStock ? 'Yes' : 'No',
                static::TABLE_COL_ACTION => $this->createButtons($productItem, $isBundleProduct),
            ];
        }

        return $result;
    }

    /**
     * @param string $neverOutOfStockSet
     * @param bool $isBundle
     *
     * @return bool
     */
    protected function isNeverOutOfStock(string $neverOutOfStockSet, bool $isBundle): bool
    {
        $hasNeverOutOfStock = strpos($neverOutOfStockSet, 'true') !== false;
        if ($isBundle && $hasNeverOutOfStock) {
            return true;
        }

        return filter_var($neverOutOfStockSet, FILTER_VALIDATE_BOOLEAN) ?: false;
    }

    /**
     * @param array $productItem
     *
     * @return int
     */
    protected function calculateReservation(array $productItem)
    {
        $quantity = (int)$productItem[AvailabilityQueryContainer::RESERVATION_QUANTITY];
        $quantity += $this->omsFacade->getReservationsFromOtherStores($productItem[AvailabilityQueryContainer::CONCRETE_SKU], $this->storeTransfer);

        return $quantity;
    }

    /**
     * @param int $idProduct
     *
     * @return bool
     */
    protected function isBundleProduct($idProduct)
    {
        if ($this->productBundleQueryContainer->queryBundleProduct($idProduct)->count() > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param array $productAbstract
     * @param bool $isBundle
     *
     * @return string
     */
    protected function createButtons(array $productAbstract, $isBundle)
    {
        if (!$isBundle) {
            $availabilityEditUrl = $this->createAvailabilityEditUrl($productAbstract);
            return $this->generateEditButton($availabilityEditUrl, 'Edit Stock');
        }
        $viewBundleUrl = $this->createViewBundleUrl($productAbstract);
        return $this->generateViewButton($viewBundleUrl, 'View bundled products');
    }

    /**
     * @param array $productAbstract
     *
     * @return string
     */
    protected function createAvailabilityEditUrl(array $productAbstract)
    {
        return Url::generate(
            '/availability-gui/index/edit',
            [
                static::URL_PARAM_ID_PRODUCT => $productAbstract[AvailabilityQueryContainer::ID_PRODUCT],
                static::URL_PARAM_SKU => $productAbstract[AvailabilityQueryContainer::CONCRETE_SKU],
                static::URL_PARAM_ID_PRODUCT_ABSTRACT => $this->idProductAbstract,
                static::URL_PARAM_ID_STORE => $this->storeTransfer->getIdStore(),
            ]
        );
    }

    /**
     * @param array $productAbstract
     *
     * @return string
     */
    protected function createViewBundleUrl(array $productAbstract)
    {
        return Url::generate(
            '/availability-gui/index/bundled-product-availability-table',
            [
                BundledProductAvailabilityTable::URL_PARAM_ID_PRODUCT_BUNDLE => $productAbstract[AvailabilityQueryContainer::ID_PRODUCT],
                BundledProductAvailabilityTable::URL_PARAM_BUNDLE_ID_PRODUCT_ABSTRACT => $this->idProductAbstract,
                static::URL_PARAM_ID_STORE => $this->storeTransfer->getIdStore(),
            ]
        );
    }
}
