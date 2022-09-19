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
    /**
     * @var string
     */
    public const TABLE_COL_ACTION = 'Actions';

    /**
     * @var string
     */
    public const URL_PARAM_ID_PRODUCT = 'id-product';

    /**
     * @var string
     */
    public const URL_PARAM_ID_PRODUCT_ABSTRACT = 'id-abstract';

    /**
     * @var string
     */
    public const URL_PARAM_SKU = 'sku';

    /**
     * @var string
     */
    public const URL_PARAM_ID_STORE = 'id-store';

    /**
     * @var string
     */
    public const URL_BACK_BUTTON = 'url-back-button';

    /**
     * @var string
     */
    public const IS_BUNDLE_PRODUCT = 'Is bundle product';

    /**
     * @var string
     */
    protected const NEVER_OUT_OF_STOCK_DEFAULT_VALUE = 'false';

    /**
     * @var string
     */
    protected const NOT_APPLICABLE = 'N/A';

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
     * @var \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
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
                $idStore,
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
        $config->addRawColumn(AvailabilityHelperInterface::CONCRETE_NEVER_OUT_OF_STOCK_SET);
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
            $isNeverOutOfStock = $this->availabilityHelper->isNeverOutOfStock($productItem[AvailabilityHelperInterface::CONCRETE_NEVER_OUT_OF_STOCK_SET] ?? static::NEVER_OUT_OF_STOCK_DEFAULT_VALUE);
            $concreteAvailability = $isNeverOutOfStock ? static::NOT_APPLICABLE : $this->formatFloat(
                (new Decimal($productItem[AvailabilityHelperInterface::CONCRETE_AVAILABILITY] ?? 0))->trim()->toFloat(),
            );
            $stockQuantity = $this->formatFloat(
                (new Decimal($productItem[AvailabilityHelperInterface::STOCK_QUANTITY] ?? 0))->trim()->toFloat(),
            );
            $reservationQuantity = $isBundleProduct ? static::NOT_APPLICABLE : $this->formatFloat(
                $this->calculateReservation($productItem)->trim()->toFloat(),
            );

            $result[] = [
                AvailabilityHelperInterface::CONCRETE_SKU => $productItem[AvailabilityHelperInterface::CONCRETE_SKU],
                AvailabilityHelperInterface::CONCRETE_NAME => $productItem[AvailabilityHelperInterface::CONCRETE_NAME],
                AvailabilityHelperInterface::CONCRETE_AVAILABILITY => $concreteAvailability,
                AvailabilityHelperInterface::STOCK_QUANTITY => $stockQuantity,
                AvailabilityHelperInterface::RESERVATION_QUANTITY => $reservationQuantity,
                static::IS_BUNDLE_PRODUCT => $this->generateLabel($isBundleProduct ? 'Yes' : 'No', null),
                AvailabilityHelperInterface::CONCRETE_NEVER_OUT_OF_STOCK_SET => $this->generateLabel($isNeverOutOfStock ? 'Yes' : 'No', null),
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
            $productItem[AvailabilityHelperInterface::CONCRETE_SKU],
            $this->storeFacade->getStoreById($this->idStore),
            $quantity,
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
                static::URL_PARAM_ID_PRODUCT => $productAbstract[AvailabilityHelperInterface::ID_PRODUCT],
                static::URL_PARAM_SKU => $productAbstract[AvailabilityHelperInterface::CONCRETE_SKU],
                static::URL_PARAM_ID_PRODUCT_ABSTRACT => $this->idProductAbstract,
                static::URL_PARAM_ID_STORE => $this->idStore,
            ],
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
                BundledProductAvailabilityTable::URL_PARAM_ID_PRODUCT_BUNDLE => $productAbstract[AvailabilityHelperInterface::ID_PRODUCT],
                BundledProductAvailabilityTable::URL_PARAM_BUNDLE_ID_PRODUCT_ABSTRACT => $this->idProductAbstract,
                static::URL_PARAM_ID_STORE => $this->idStore,
            ],
        );
    }
}
