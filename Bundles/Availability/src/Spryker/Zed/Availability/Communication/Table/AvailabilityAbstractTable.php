<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Communication\Table;

use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Shared\Url\Url;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AvailabilityAbstractTable extends AbstractTable
{

    const TABLE_COL_ACTION = 'Actions';
    const URL_PARAM_ID_PRODUCT_ABSTRACT = 'id-product';
    const AVAILABLE = 'Available';
    const NOT_AVAILABLE = 'Not available';

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected $queryProductAbstractAvailability;

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $queryProductAbstractAvailability
     */
    public function __construct(SpyProductAbstractQuery $queryProductAbstractAvailability)
    {
        $this->queryProductAbstractAvailability = $queryProductAbstractAvailability;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $url = Url::generate('/availability-abstract-table');

        $config->setUrl($url);
        $config->setHeader([
            SpyProductAbstractTableMap::COL_SKU => 'SKU',
            AvailabilityQueryContainer::PRODUCT_NAME => 'Name',
            SpyAvailabilityAbstractTableMap::COL_QUANTITY => 'Availability',
            AvailabilityQueryContainer::STOCK_QUANTITY => 'Current Stock',
            AvailabilityQueryContainer::RESERVATION_QUANTITY => 'Reserved Products',
            self::TABLE_COL_ACTION => 'Actions',
        ]);

        $config->setSortable([
            SpyProductAbstractTableMap::COL_SKU,
            AvailabilityQueryContainer::PRODUCT_NAME,
            AvailabilityQueryContainer::STOCK_QUANTITY,
            AvailabilityQueryContainer::RESERVATION_QUANTITY,
        ]);

        $config->setSearchable([
            SpyProductAbstractTableMap::COL_SKU,
            AvailabilityQueryContainer::PRODUCT_NAME => 'Name',
        ]);

        $config->setDefaultSortColumnIndex(0);
        $config->addRawColumn(self::TABLE_COL_ACTION);
        $config->addRawColumn(SpyAvailabilityAbstractTableMap::COL_QUANTITY);
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

        foreach ($queryResult as $productAbstract) {
            $result[] = [
                SpyProductAbstractTableMap::COL_SKU => $productAbstract->getSku(),
                AvailabilityQueryContainer::PRODUCT_NAME => $productAbstract->getProductName(),
                SpyAvailabilityAbstractTableMap::COL_QUANTITY => $this->getAvailabilityLabel($productAbstract->getAvailabilityQuantity()),
                AvailabilityQueryContainer::STOCK_QUANTITY => $productAbstract->getStockQuantity(),
                AvailabilityQueryContainer::RESERVATION_QUANTITY => $this->calculateReservation($productAbstract->getReservationQuantity()),
                self::TABLE_COL_ACTION => $this->createViewButton($productAbstract),
            ];
        }

        return $result;
    }

    /**
     * @param int $quantity
     *
     * @return string
     */
    protected function getAvailabilityLabel($quantity)
    {
        if ($quantity > 0) {
            return '<span class="label label-info">' . self::AVAILABLE . '</span>';
        }
        return '<span class="label">' . self::NOT_AVAILABLE . '</span>';
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function createViewButton(SpyProductAbstract $productAbstractEntity)
    {
        $viewTaxSetUrl = Url::generate(
            '/availability/index/view',
            [
                self::URL_PARAM_ID_PRODUCT_ABSTRACT => $productAbstractEntity->getIdProductAbstract(),
            ]
        );
        return $this->generateViewButton($viewTaxSetUrl, 'View');
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
            $value = explode(':', $item);

            if (count($value) > 1) {
                $reservation += (int)$value[1];
            }
        }

        return $reservation;
    }

}
