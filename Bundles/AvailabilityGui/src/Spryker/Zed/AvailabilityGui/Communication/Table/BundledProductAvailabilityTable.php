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
use Orm\Zed\ProductBundle\Persistence\Map\SpyProductBundleTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\DecimalObject\Decimal;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToOmsFacadeInterface;
use Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class BundledProductAvailabilityTable extends AbstractTable
{
    public const URL_PARAM_ID_PRODUCT_BUNDLE = 'id-product';
    public const URL_PARAM_ID_PRODUCT_ABSTRACT = 'id-abstract';
    public const URL_PARAM_SKU = 'sku';
    public const URL_PARAM_ID_PRODUCT = 'id-product';
    public const URL_PARAM_BUNDLE_ID_PRODUCT_ABSTRACT = 'id-product-bundle-abstract';
    public const URL_PARAM_ID_STORE = 'id-store';

    public const COL_BUNDLED_ITEMS = 'bundledItems';
    public const TABLE_COL_ACTION = 'Actions';

    /**
     * @var int
     */
    protected $idProductBundle;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected $productAbstractQuery;

    /**
     * @var \Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerInterface
     */
    protected $productBundleQueryContainer;

    /**
     * @var int
     */
    protected $idLocale;

    /**
     * @var int
     */
    protected $idBundleProductAbstract;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected $storeTransfer;

    /**
     * @var \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToOmsFacadeInterface
     */
    private $omsFacade;

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $productAbstractQuery
     * @param \Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerInterface $productBundleQueryContainer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToOmsFacadeInterface $omsFacade
     * @param int|null $idProductBundle
     * @param int|null $idBundleProductAbstract
     */
    public function __construct(
        SpyProductAbstractQuery $productAbstractQuery,
        AvailabilityGuiToProductBundleQueryContainerInterface $productBundleQueryContainer,
        StoreTransfer $storeTransfer,
        AvailabilityGuiToOmsFacadeInterface $omsFacade,
        $idProductBundle = null,
        $idBundleProductAbstract = null
    ) {
        $this->productAbstractQuery = $productAbstractQuery;
        $this->idProductBundle = $idProductBundle;
        $this->productBundleQueryContainer = $productBundleQueryContainer;
        $this->idBundleProductAbstract = $idBundleProductAbstract;
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
        $url = Url::generate('bundled-product-availability-table', [
            BundledProductAvailabilityTable::URL_PARAM_ID_PRODUCT_BUNDLE => $this->idProductBundle,
            BundledProductAvailabilityTable::URL_PARAM_ID_PRODUCT_ABSTRACT => $this->idBundleProductAbstract,
            static::URL_PARAM_ID_STORE => $this->storeTransfer->getIdStore(),
        ])->build();

        $config->setUrl($url);

        $config->setHeader([
            AvailabilityQueryContainer::CONCRETE_SKU => 'SKU',
            AvailabilityQueryContainer::CONCRETE_NAME => 'Name',
            AvailabilityQueryContainer::CONCRETE_AVAILABILITY => 'Availability',
            AvailabilityQueryContainer::STOCK_QUANTITY => 'Current Stock',
            AvailabilityQueryContainer::RESERVATION_QUANTITY => 'Reserved Products',
            SpyProductBundleTableMap::COL_QUANTITY => 'Quantity in Bundle',
            AvailabilityQueryContainer::CONCRETE_NEVER_OUT_OF_STOCK_SET => 'Is never out of stock',
            static::TABLE_COL_ACTION => 'Actions',
        ]);

        $config->setSortable([
            AvailabilityQueryContainer::CONCRETE_SKU,
            AvailabilityQueryContainer::CONCRETE_NAME,
            AvailabilityQueryContainer::CONCRETE_AVAILABILITY,
            AvailabilityQueryContainer::STOCK_QUANTITY,
            AvailabilityQueryContainer::RESERVATION_QUANTITY,
        ]);

        $config->setSearchable([
            SpyProductTableMap::COL_SKU,
            SpyProductLocalizedAttributesTableMap::COL_NAME,
        ]);

        $config->setRawColumns([static::TABLE_COL_ACTION]);

        $config->setDefaultSortColumnIndex(0);
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
        if (!$this->idProductBundle) {
            return [];
        }

        $bundledProducts = $this->productBundleQueryContainer
            ->queryBundleProduct($this->idProductBundle)
            ->select([SpyProductBundleTableMap::COL_FK_BUNDLED_PRODUCT])
            ->find();

        if ($bundledProducts->count() === 0) {
            return [];
        }

        $ids = $bundledProducts->toArray();

        $queryProductAbstractAvailability = $this->productAbstractQuery
            ->addJoin(SpyProductTableMap::COL_ID_PRODUCT, SpyProductBundleTableMap::COL_FK_BUNDLED_PRODUCT, Criteria::INNER_JOIN)
            ->withColumn(SpyProductBundleTableMap::COL_QUANTITY, static::COL_BUNDLED_ITEMS)
            ->addOr(SpyProductTableMap::COL_ID_PRODUCT, $ids, Criteria::IN)
            ->addAnd(SpyProductBundleTableMap::COL_FK_PRODUCT, $this->idProductBundle);

        $queryResult = $this->runQuery($queryProductAbstractAvailability, $config, true);

        $result = [];
        foreach ($queryResult as $productItem) {
            $neverOutOfStockFlag = 'n/a';
            if ($productItem[AvailabilityQueryContainer::CONCRETE_NEVER_OUT_OF_STOCK_SET]) {
                $neverOutOfStockFlag = $this->isNeverOutOfStock($productItem[AvailabilityQueryContainer::CONCRETE_NEVER_OUT_OF_STOCK_SET]) ? 'Yes' : 'No';
            }

            $result[] = [
                AvailabilityQueryContainer::CONCRETE_SKU => $productItem[AvailabilityQueryContainer::CONCRETE_SKU],
                AvailabilityQueryContainer::CONCRETE_NAME => $productItem[AvailabilityQueryContainer::CONCRETE_NAME],
                AvailabilityQueryContainer::CONCRETE_AVAILABILITY => $this->getConcreteAvailability($productItem)->trim(),
                AvailabilityQueryContainer::STOCK_QUANTITY => $this->getStock($productItem)->trim(),
                AvailabilityQueryContainer::RESERVATION_QUANTITY => $this->calculateReservation($productItem)->trim(),
                SpyProductBundleTableMap::COL_QUANTITY => $productItem[static::COL_BUNDLED_ITEMS],
                AvailabilityQueryContainer::CONCRETE_NEVER_OUT_OF_STOCK_SET => $neverOutOfStockFlag,
                static::TABLE_COL_ACTION => $this->createEditButton($productItem),
            ];
        }

        return $result;
    }

    /**
     * @param string $neverOutOfStockSet
     *
     * @return bool
     */
    protected function isNeverOutOfStock($neverOutOfStockSet)
    {
        $statusSet = explode(',', $neverOutOfStockSet);

        foreach ($statusSet as $status) {
            if (filter_var($status, FILTER_VALIDATE_BOOLEAN)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $productItem
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function calculateReservation(array $productItem): Decimal
    {
        $reservation = $this->omsFacade->getReservationsFromOtherStores($productItem[AvailabilityQueryContainer::CONCRETE_SKU], $this->storeTransfer);

        return (new Decimal($productItem[AvailabilityQueryContainer::RESERVATION_QUANTITY] ?? 0))
            ->add($reservation);
    }

    /**
     * @param array $productItem
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function getStock(array $productItem): Decimal
    {
        return (new Decimal($productItem[AvailabilityQueryContainer::STOCK_QUANTITY] ?? 0));
    }

    /**
     * @param array $productItem
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function getConcreteAvailability(array $productItem): Decimal
    {
        return (new Decimal($productItem[AvailabilityQueryContainer::CONCRETE_AVAILABILITY] ?? 0));
    }

    /**
     * @param array $productItem
     *
     * @return string
     */
    protected function createEditButton(array $productItem)
    {
        $availabilityEditUrl = Url::generate(
            '/availability-gui/index/edit',
            [
                static::URL_PARAM_ID_PRODUCT => $productItem[AvailabilityQueryContainer::ID_PRODUCT],
                static::URL_PARAM_SKU => $productItem[AvailabilityQueryContainer::CONCRETE_SKU],
                static::URL_PARAM_ID_PRODUCT_ABSTRACT => $this->idBundleProductAbstract,
                static::URL_PARAM_ID_STORE => $this->storeTransfer->getIdStore(),
            ]
        );

        return $this->generateEditButton($availabilityEditUrl, 'Edit Stock');
    }
}
