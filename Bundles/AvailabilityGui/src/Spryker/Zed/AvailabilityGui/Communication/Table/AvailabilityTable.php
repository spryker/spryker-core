<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer;
use Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AvailabilityTable extends AbstractTable
{
    const TABLE_COL_ACTION = 'Actions';
    const URL_PARAM_ID_PRODUCT = 'id-product';
    const URL_PARAM_ID_PRODUCT_ABSTRACT = 'id-abstract';
    const URL_PARAM_SKU = 'sku';
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
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $queryProductAbstractAvailabilityGui
     * @param int $idProductAbstract
     * @param \Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerInterface $productBundleQueryContainer
     */
    public function __construct(
        SpyProductAbstractQuery $queryProductAbstractAvailabilityGui,
        $idProductAbstract,
        AvailabilityGuiToProductBundleQueryContainerInterface $productBundleQueryContainer
    ) {

        $this->setTableIdentifier('availability-table');

        $this->queryProductAbstractAvailability = $queryProductAbstractAvailabilityGui;
        $this->idProductAbstract = $idProductAbstract;
        $this->productBundleQueryContainer = $productBundleQueryContainer;
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

            $result[] = [
                AvailabilityQueryContainer::CONCRETE_SKU => $productItem[AvailabilityQueryContainer::CONCRETE_SKU],
                AvailabilityQueryContainer::CONCRETE_NAME => $productItem[AvailabilityQueryContainer::CONCRETE_NAME],
                AvailabilityQueryContainer::CONCRETE_AVAILABILITY => $productItem[AvailabilityQueryContainer::CONCRETE_AVAILABILITY],
                AvailabilityQueryContainer::STOCK_QUANTITY => $productItem[AvailabilityQueryContainer::STOCK_QUANTITY],
                AvailabilityQueryContainer::RESERVATION_QUANTITY => ($isBundleProduct) ? 'N/A' : $productItem[AvailabilityQueryContainer::RESERVATION_QUANTITY] ?: 0,
                static::IS_BUNDLE_PRODUCT => ($isBundleProduct) ? 'Yes' : 'No',
                AvailabilityQueryContainer::CONCRETE_NEVER_OUT_OF_STOCK_SET => ($productItem[AvailabilityQueryContainer::CONCRETE_NEVER_OUT_OF_STOCK_SET]) ? 'Yes' : 'No',
                static::TABLE_COL_ACTION => $this->createEditButton($productItem, $isBundleProduct),
            ];
        }

        return $result;
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
    protected function createEditButton(array $productAbstract, $isBundle)
    {
        $availabilityEditUrl = Url::generate(
            '/availability-gui/index/edit',
            [
                static::URL_PARAM_ID_PRODUCT => $productAbstract[AvailabilityQueryContainer::ID_PRODUCT],
                static::URL_PARAM_SKU => $productAbstract[AvailabilityQueryContainer::CONCRETE_SKU],
                static::URL_PARAM_ID_PRODUCT_ABSTRACT => $this->idProductAbstract,
            ]
        );

        $buttons = '';
        if (!$isBundle) {
            $buttons = $this->generateEditButton($availabilityEditUrl, 'Edit Stock');
        } else {
            $viewBundleUrl = Url::generate(
                '/availability-gui/index/bundled-product-availability-table',
                [
                    BundledProductAvailabilityTable::URL_PARAM_ID_PRODUCT_BUNDLE => $productAbstract[AvailabilityQueryContainer::ID_PRODUCT],
                    BundledProductAvailabilityTable::URL_PARAM_BUNDLE_ID_PRODUCT_ABSTRACT => $this->idProductAbstract,
                ]
            );

            $buttons .= ' ' . $this->generateViewButton($viewBundleUrl, 'View bundled products');
        }

        return $buttons;
    }
}
