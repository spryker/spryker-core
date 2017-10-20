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
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer;
use Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToAvailabilityQueryContainerInterface;
use Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class BundledProductAvailabilityTable extends AbstractTable
{
    const URL_PARAM_ID_PRODUCT_BUNDLE = 'id-product';
    const URL_PARAM_ID_PRODUCT_ABSTRACT = 'id-abstract';
    const URL_PARAM_SKU = 'sku';
    const URL_PARAM_ID_PRODUCT = 'id-product';
    const URL_PARAM_BUNDLE_ID_PRODUCT_ABSTRACT = 'id-product-bundle-abstract';

    const COL_BUNDLED_ITEMS = 'bundledItems';
    const TABLE_COL_ACTION = 'Actions';

    /**
     * @var int
     */
    protected $idProductBundle;

    /**
     * @var \Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToAvailabilityQueryContainerInterface
     */
    protected $availabilityQueryContainer;

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
     * @param \Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToAvailabilityQueryContainerInterface $availabilityQueryContainer
     * @param \Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerInterface $productBundleQueryContainer
     * @param int $idLocale
     * @param int|null $idProductBundle
     * @param int|null $idBundleProductAbstract
     */
    public function __construct(
        AvailabilityGuiToAvailabilityQueryContainerInterface $availabilityQueryContainer,
        AvailabilityGuiToProductBundleQueryContainerInterface $productBundleQueryContainer,
        $idLocale,
        $idProductBundle = null,
        $idBundleProductAbstract = null
    ) {
        $this->availabilityQueryContainer = $availabilityQueryContainer;
        $this->idProductBundle = $idProductBundle;
        $this->idLocale = $idLocale;
        $this->productBundleQueryContainer = $productBundleQueryContainer;
        $this->idBundleProductAbstract = $idBundleProductAbstract;
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

        $queryProductAbstractAvailability = $this->availabilityQueryContainer
            ->queryAvailabilityWithStockByIdProductAbstractAndIdLocale($this->idProductBundle, $this->idLocale)
            ->addJoin(SpyProductTableMap::COL_ID_PRODUCT, SpyProductBundleTableMap::COL_FK_BUNDLED_PRODUCT, Criteria::INNER_JOIN)
            ->withColumn(SpyProductBundleTableMap::COL_QUANTITY, static::COL_BUNDLED_ITEMS)
            ->addOr(SpyProductTableMap::COL_ID_PRODUCT, $ids, Criteria::IN)
            ->addAnd(SpyProductBundleTableMap::COL_FK_PRODUCT, $this->idProductBundle);

        $queryResult = $this->runQuery($queryProductAbstractAvailability, $config, true);

        $result = [];
        foreach ($queryResult as $productItem) {
            $result[] = [
                AvailabilityQueryContainer::CONCRETE_SKU => $productItem[AvailabilityQueryContainer::CONCRETE_SKU],
                AvailabilityQueryContainer::CONCRETE_NAME => $productItem[AvailabilityQueryContainer::CONCRETE_NAME],
                AvailabilityQueryContainer::CONCRETE_AVAILABILITY => $productItem[AvailabilityQueryContainer::CONCRETE_AVAILABILITY],
                AvailabilityQueryContainer::STOCK_QUANTITY => $productItem[AvailabilityQueryContainer::STOCK_QUANTITY],
                AvailabilityQueryContainer::RESERVATION_QUANTITY => $productItem[AvailabilityQueryContainer::RESERVATION_QUANTITY],
                SpyProductBundleTableMap::COL_QUANTITY => $productItem[static::COL_BUNDLED_ITEMS],
                AvailabilityQueryContainer::CONCRETE_NEVER_OUT_OF_STOCK_SET => $productItem[AvailabilityQueryContainer::CONCRETE_NEVER_OUT_OF_STOCK_SET],
                static::TABLE_COL_ACTION => $this->createEditButton($productItem),
            ];
        }

        return $result;
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
            ]
        );

        return $this->generateEditButton($availabilityEditUrl, 'Edit Stock');
    }
}
