<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductBundle\Persistence\Map\SpyProductBundleTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\DecimalObject\Decimal;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer;
use Spryker\Zed\AvailabilityGui\Communication\Helper\AvailabilityHelperInterface;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityToStoreFacadeInterface;
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
     * @var \Spryker\Zed\AvailabilityGui\Communication\Helper\AvailabilityHelperInterface
     */
    protected $availabilityHelper;

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
    protected $idStore;

    /**
     * @var int
     */
    protected $idBundleProductAbstract;

    /**
     * @var \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    private $storeFacade;

    /**
     * @param \Spryker\Zed\AvailabilityGui\Communication\Helper\AvailabilityHelperInterface $availabilityHelper
     * @param \Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerInterface $productBundleQueryContainer
     * @param \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     * @param int $idLocale
     * @param int $idStore
     * @param int $idProductBundle
     * @param int $idBundleProductAbstract
     */
    public function __construct(
        AvailabilityHelperInterface $availabilityHelper,
        AvailabilityGuiToProductBundleQueryContainerInterface $productBundleQueryContainer,
        AvailabilityToStoreFacadeInterface $storeFacade,
        int $idLocale,
        int $idStore,
        int $idProductBundle,
        int $idBundleProductAbstract
    ) {
        $this->availabilityHelper = $availabilityHelper;
        $this->productBundleQueryContainer = $productBundleQueryContainer;
        $this->storeFacade = $storeFacade;
        $this->idStore = $idStore;
        $this->idProductBundle = $idProductBundle;
        $this->idBundleProductAbstract = $idBundleProductAbstract;

        $this->productAbstractQuery = $this->availabilityHelper->queryAvailabilityWithStockByIdProductAbstractAndIdLocale(
            $idProductBundle,
            $idLocale,
            $idStore
        );
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
            static::URL_PARAM_ID_STORE => $this->idStore,
        ])->build();

        $config->setUrl($url);

        $config->setHeader([
            AvailabilityHelperInterface::CONCRETE_SKU => 'SKU',
            AvailabilityHelperInterface::CONCRETE_NAME => 'Name',
            AvailabilityHelperInterface::CONCRETE_AVAILABILITY => 'Availability',
            AvailabilityHelperInterface::STOCK_QUANTITY => 'Current Stock',
            AvailabilityHelperInterface::RESERVATION_QUANTITY => 'Reserved Products',
            SpyProductBundleTableMap::COL_QUANTITY => 'Quantity in Bundle',
            AvailabilityHelperInterface::CONCRETE_NEVER_OUT_OF_STOCK_SET => 'Is never out of stock',
            static::TABLE_COL_ACTION => 'Actions',
        ]);

        $config->setSortable([
            AvailabilityHelperInterface::CONCRETE_SKU,
            AvailabilityHelperInterface::CONCRETE_NAME,
            AvailabilityHelperInterface::CONCRETE_AVAILABILITY,
            AvailabilityHelperInterface::STOCK_QUANTITY,
            AvailabilityHelperInterface::RESERVATION_QUANTITY,
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
            if ($productItem[AvailabilityHelperInterface::CONCRETE_NEVER_OUT_OF_STOCK_SET]) {
                $neverOutOfStockFlag = $this->availabilityHelper->isNeverOutOfStock($productItem[AvailabilityHelperInterface::CONCRETE_NEVER_OUT_OF_STOCK_SET]) ? 'Yes' : 'No';
            }

            $result[] = [
                AvailabilityQueryContainer::CONCRETE_SKU => $productItem[AvailabilityQueryContainer::CONCRETE_SKU],
                AvailabilityQueryContainer::CONCRETE_NAME => $productItem[AvailabilityQueryContainer::CONCRETE_NAME],
                AvailabilityQueryContainer::CONCRETE_AVAILABILITY => $this->getConcreteAvailability($productItem)->trim(),
                AvailabilityHelperInterface::STOCK_QUANTITY => $this->getStockQuantity($productItem)->trim(),
                AvailabilityHelperInterface::RESERVATION_QUANTITY => $this->calculateReservation($productItem)->trim(),
                SpyProductBundleTableMap::COL_QUANTITY => $productItem[static::COL_BUNDLED_ITEMS],
                AvailabilityHelperInterface::CONCRETE_NEVER_OUT_OF_STOCK_SET => $neverOutOfStockFlag,
                static::TABLE_COL_ACTION => $this->createEditButton($productItem),
            ];
        }

        return $result;
    }

    /**
     * @param array $productItem
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function calculateReservation(array $productItem): Decimal
    {
        $reservationQuantity = new Decimal($productItem[AvailabilityHelperInterface::RESERVATION_QUANTITY] ?? 0);

        return $this->availabilityHelper->sumReservationsFromOtherStores(
            $productItem[AvailabilityQueryContainer::CONCRETE_SKU],
            $this->storeFacade->getStoreById($this->idStore),
            $reservationQuantity
        );
    }

    /**
     * @param array $productItem
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function getStockQuantity(array $productItem): Decimal
    {
        return (new Decimal($productItem[AvailabilityHelperInterface::STOCK_QUANTITY] ?? 0));
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
                static::URL_PARAM_ID_STORE => $this->idStore,
            ]
        );

        return $this->generateEditButton($availabilityEditUrl, 'Edit Stock');
    }
}
