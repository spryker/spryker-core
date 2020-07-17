<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferStoreTableMap;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\ProductOfferGui\ProductOfferGuiConfig;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class OfferTable extends AbstractTable
{
    protected const REQUEST_ID_PRODUCT_OFFER = 'id-product-offer';

    public const COL_STORES = 'stores';
    public const COL_ACTIONS = 'actions';
    protected const COL_NAME = 'name';

    protected const STORE_CLASS_LABEL = 'label-info';

    protected const APPROVAL_STATUS_CLASS_LABEL_MAPPING = [
        ProductOfferGuiConfig::STATUS_WAITING_FOR_APPROVAL => 'label-warning',
        ProductOfferGuiConfig::STATUS_APPROVED => 'label-info',
        ProductOfferGuiConfig::STATUS_DECLINED => 'label-danger',
    ];

    /**
     * @var \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected $productOfferQuery;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     */
    public function __construct(
        SpyProductOfferQuery $productOfferQuery,
        LocaleTransfer $localeTransfer
    ) {
        $this->productOfferQuery = $productOfferQuery;
        $this->localeTransfer = $localeTransfer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = $this->setHeader($config);

        $config->setSortable([
            SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER,
            SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE,
            SpyProductOfferTableMap::COL_CONCRETE_SKU,
            //SpyProductLocalizedAttributesTableMap::COL_NAME,
            SpyProductOfferTableMap::COL_APPROVAL_STATUS,
            SpyProductOfferTableMap::COL_IS_ACTIVE,
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
            SpyProductOfferTableMap::COL_APPROVAL_STATUS,
            SpyProductOfferTableMap::COL_IS_ACTIVE,
            SpyProductOfferStoreTableMap::COL_FK_STORE,
        ]);
        $config->setDefaultSortField(SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER, TableConfiguration::SORT_DESC);

        $config->setSearchable([
            SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER,
            SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE,
            SpyProductOfferTableMap::COL_CONCRETE_SKU,
            //SpyProductLocalizedAttributesTableMap::COL_NAME,
            SpyProductOfferTableMap::COL_APPROVAL_STATUS,
            SpyProductOfferTableMap::COL_IS_ACTIVE,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $baseData = [
            SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER => 'Offer ID',
            SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => 'Reference',
            SpyProductOfferTableMap::COL_CONCRETE_SKU => 'SKU',
            SpyProductLocalizedAttributesTableMap::COL_NAME => 'Name',
            SpyProductOfferTableMap::COL_APPROVAL_STATUS => 'Status',
            SpyProductOfferTableMap::COL_IS_ACTIVE => 'Visibility',
            SpyProductOfferStoreTableMap::COL_FK_STORE => 'Stores',
            static::COL_ACTIONS => 'Actions',
        ];

        $config->setHeader($baseData);

        return $config;
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function prepareQuery(): SpyProductOfferQuery
    {
        $this->productOfferQuery
            ->groupByIdProductOffer()
            ->useSpyProductOfferStoreQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinSpyStore()
                ->withColumn(
                    sprintf('GROUP_CONCAT(%s)', SpyStoreTableMap::COL_NAME),
                    static::COL_STORES
                )
            ->endUse()
            ->addJoin(SpyProductOfferTableMap::COL_CONCRETE_SKU, SpyProductTableMap::COL_SKU, Criteria::INNER_JOIN)
            ->addJoin(SpyProductTableMap::COL_ID_PRODUCT, SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT, Criteria::INNER_JOIN)
            ->where(sprintf('%s = (%s)', SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE, $this->localeTransfer->getIdLocale()))
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, static::COL_NAME);

        return $this->productOfferQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->prepareQuery(), $config);
        $results = [];

        foreach ($queryResults as $item) {
            $rowData = [
                SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER => $item[SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER],
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $item[SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE],
                SpyProductOfferTableMap::COL_CONCRETE_SKU => $item[SpyProductOfferTableMap::COL_CONCRETE_SKU],
                SpyProductLocalizedAttributesTableMap::COL_NAME => $item[static::COL_NAME],
                SpyProductOfferTableMap::COL_APPROVAL_STATUS => $this->createStatusLabel($item),
                SpyProductOfferTableMap::COL_IS_ACTIVE => $this->getActiveLabel($item[SpyProductOfferTableMap::COL_IS_ACTIVE]),
                SpyProductOfferStoreTableMap::COL_FK_STORE => $this->createStoresLabel($item),
                static::COL_ACTIONS => $this->buildLinks($item),
            ];
            $results[] = $rowData;
        }

        unset($queryResults);

        return $results;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function buildLinks(array $item): string
    {
        $buttons = [];

        return implode(' ', $buttons);
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function createStatusLabel(array $item): string
    {
        $currentStatus = $item[SpyProductOfferTableMap::COL_APPROVAL_STATUS];

        if (!isset(static::APPROVAL_STATUS_CLASS_LABEL_MAPPING[$currentStatus])) {
            return $currentStatus;
        }

        return $this->generateLabel($currentStatus, static::APPROVAL_STATUS_CLASS_LABEL_MAPPING[$currentStatus]);
    }

    /**
     * @param bool $isActive
     *
     * @return string
     */
    public function getActiveLabel(bool $isActive): string
    {
        return $isActive ? $this->generateLabel('Active', static::STORE_CLASS_LABEL) : $this->generateLabel('Inactive', static::STORE_CLASS_LABEL);
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function createStoresLabel(array $item): string
    {
        $storeNames = explode(',', $item[static::COL_STORES]);

        $storeLabels = [];
        foreach ($storeNames as $storeName) {
            $storeLabels[] = $this->generateLabel($storeName, static::STORE_CLASS_LABEL);
        }

        return implode(' ', $storeLabels);
    }
}
