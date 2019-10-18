<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\DecimalObject\Decimal;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer;
use Spryker\Zed\AvailabilityGui\Communication\Helper\AvailabilityHelperInterface;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AvailabilityTable extends AbstractTable
{
    public const TABLE_COL_ACTION = 'Actions';
    public const URL_PARAM_ID_PRODUCT = 'id-product';
    public const URL_PARAM_ID_PRODUCT_ABSTRACT = 'id-abstract';
    public const URL_PARAM_SKU = 'sku';
    public const URL_PARAM_ID_STORE = 'id-store';
    public const URL_BACK_BUTTON = 'url-back-button';

    public const IS_BUNDLE_PRODUCT = 'Is bundle product';

    /**
     * @var int
     */
    protected $idProductAbstract;

    /**
     * @var int
     */
    protected $idStore;

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
     * @param \Spryker\Zed\AvailabilityGui\Communication\Helper\AvailabilityHelperInterface $availabilityHelper
     * @param \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     * @param int $idProductAbstract
     * @param int $idLocale
     * @param int $idStore
     */
    public function __construct(
        AvailabilityHelperInterface $availabilityHelper,
        AvailabilityToStoreFacadeInterface $storeFacade,
        int $idProductAbstract,
        int $idLocale,
        int $idStore
    ) {
        $this->setTableIdentifier('availability-table');

        $this->availabilityHelper = $availabilityHelper;
        $this->storeFacade = $storeFacade;
        $this->idProductAbstract = $idProductAbstract;
        $this->idStore = $idStore;

        $this->queryProductAbstractAvailability = $this->availabilityHelper
            ->queryAvailabilityWithStockByIdProductAbstractAndIdLocale(
                $idProductAbstract,
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
        $url = Url::generate('availability-table', [
            AvailabilityAbstractTable::URL_PARAM_ID_PRODUCT_ABSTRACT => $this->idProductAbstract,
            static::URL_PARAM_ID_STORE => $this->idStore,
        ])->build();

        $config->setUrl($url);

        $config->setHeader([
            AvailabilityHelperInterface::CONCRETE_SKU => 'SKU',
            AvailabilityHelperInterface::CONCRETE_NAME => 'Name',
            AvailabilityHelperInterface::CONCRETE_AVAILABILITY => 'Availability',
            AvailabilityHelperInterface::STOCK_QUANTITY => 'Current Stock',
            AvailabilityHelperInterface::RESERVATION_QUANTITY => 'Reserved Products',
            static::IS_BUNDLE_PRODUCT => 'Is bundle product',
            AvailabilityHelperInterface::CONCRETE_NEVER_OUT_OF_STOCK_SET => 'Is never out of stock',
            static::TABLE_COL_ACTION => 'Actions',
        ]);

        $config->setSortable([
            AvailabilityHelperInterface::CONCRETE_SKU,
            AvailabilityHelperInterface::CONCRETE_NAME,
            AvailabilityHelperInterface::STOCK_QUANTITY,
            AvailabilityHelperInterface::RESERVATION_QUANTITY,
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
            $isBundleProduct = $this->availabilityHelper->isBundleProduct($productItem[AvailabilityQueryContainer::ID_PRODUCT]);

            $isNeverOutOfStock = $this->availabilityHelper->isNeverOutOfStock($productItem[AvailabilityHelperInterface::CONCRETE_NEVER_OUT_OF_STOCK_SET]);

            $result[] = [
                AvailabilityHelperInterface::CONCRETE_SKU => $productItem[AvailabilityQueryContainer::CONCRETE_SKU],
                AvailabilityHelperInterface::CONCRETE_NAME => $productItem[AvailabilityQueryContainer::CONCRETE_NAME],
                AvailabilityHelperInterface::CONCRETE_AVAILABILITY => (new Decimal($productItem[AvailabilityQueryContainer::CONCRETE_AVAILABILITY] ?? 0))->trim(),
                AvailabilityHelperInterface::STOCK_QUANTITY => (new Decimal($productItem[AvailabilityHelperInterface::STOCK_QUANTITY] ?? 0))->trim(),
                AvailabilityHelperInterface::RESERVATION_QUANTITY => ($isBundleProduct) ? 'N/A' : $this->calculateReservation($productItem)->trim(),
                static::IS_BUNDLE_PRODUCT => ($isBundleProduct) ? 'Yes' : 'No',
                AvailabilityHelperInterface::CONCRETE_NEVER_OUT_OF_STOCK_SET => $isNeverOutOfStock ? 'Yes' : 'No',
                static::TABLE_COL_ACTION => $this->createButtons($productItem, $isBundleProduct),
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
        $quantity = new Decimal($productItem[AvailabilityHelperInterface::RESERVATION_QUANTITY] ?? 0);

        return $this->availabilityHelper->sumReservationsFromOtherStores(
            $productItem[AvailabilityQueryContainer::CONCRETE_SKU],
            $this->storeFacade->getStoreById($this->idStore),
            $quantity
        );
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
                static::URL_PARAM_ID_STORE => $this->idStore,
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
                static::URL_PARAM_ID_STORE => $this->idStore,
            ]
        );
    }
}
