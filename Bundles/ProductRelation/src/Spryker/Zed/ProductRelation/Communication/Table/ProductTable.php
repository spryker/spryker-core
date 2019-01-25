<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductRelation\Communication\Controller\ViewController;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToLocaleInterface;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToMoneyInterface;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToPriceProductFacadeInterface;
use Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainer;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface;

class ProductTable extends AbstractProductTable
{
    public const COL_ACTIONS = 'Actions';
    public const COL_STATUS = 'Status';

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface
     */
    protected $productRelationQueryContainer;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var int|null
     */
    protected $idProductRelation;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface $productRelationQueryContainer
     * @param \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToMoneyInterface $moneyFacade
     * @param \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToPriceProductFacadeInterface $priceProductFacade
     * @param int|null $idProductRelation
     */
    public function __construct(
        ProductRelationQueryContainerInterface $productRelationQueryContainer,
        ProductRelationToLocaleInterface $localeFacade,
        ProductRelationToUtilEncodingInterface $utilEncodingService,
        ProductRelationToMoneyInterface $moneyFacade,
        ProductRelationToPriceProductFacadeInterface $priceProductFacade,
        $idProductRelation = null
    ) {
        $this->productRelationQueryContainer = $productRelationQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->utilEncodingService = $utilEncodingService;

        $this->setTableIdentifier('product-table');
        $this->idProductRelation = $idProductRelation;
        $this->moneyFacade = $moneyFacade;
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->setTableUrl($config);
        $this->setHeaders($config);
        $this->setRawColumns($config);
        $this->setSortableFields($config);
        $this->setSearchableFields($config);
        $this->setDefaultSortField($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setHeaders(TableConfiguration $config)
    {
        $header = [
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => '#',
            SpyProductAbstractTableMap::COL_SKU => 'Sku',
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => 'Name',
            ProductRelationQueryContainer::COL_PRICE_PRODUCT => 'Price',
            ProductRelationQueryContainer::COL_ASSIGNED_CATEGORIES => 'Categories',
            static::COL_STATUS => 'Status',
        ];

        if ($this->idProductRelation === null) {
            $header[static::COL_ACTIONS] = static::COL_ACTIONS;
        }

        $config->setHeader($header);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setRawColumns(TableConfiguration $config)
    {
        $config->setRawColumns([
            static::COL_ACTIONS,
            static::COL_STATUS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSortableFields(TableConfiguration $config)
    {
        $config->setSortable([
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            SpyProductAbstractTableMap::COL_SKU,
            ProductRelationQueryContainer::COL_PRICE_PRODUCT,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSearchableFields(TableConfiguration $config)
    {
        $config->setSearchable([
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setDefaultSortField(TableConfiguration $config)
    {
        $config->setDefaultSortField(
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            TableConfiguration::SORT_DESC
        );
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setTableUrl(TableConfiguration $config)
    {
        $url = Url::generate(
            'table',
            [
                ViewController::URL_PARAM_ID_PRODUCT_RELATION => $this->idProductRelation,
            ]
        )->build();

        $config->setUrl($url);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();

        if ($this->idProductRelation !== null) {
            $query = $this->productRelationQueryContainer->queryProductsWithCategoriesRelationsByFkLocaleAndIdRelation(
                $localeTransfer->getIdLocale(),
                $this->idProductRelation
            );
        } else {
            $query = $this->productRelationQueryContainer
                ->queryProductsWithCategoriesByFkLocale($localeTransfer->getIdLocale());
        }

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $item) {
            $results[] = $this->mapResults($item);
        }

        return $results;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function mapResults(array $item)
    {
        $results = [
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => $item[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT],
            SpyProductAbstractTableMap::COL_SKU => $item[SpyProductAbstractTableMap::COL_SKU],
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => $item[SpyProductAbstractLocalizedAttributesTableMap::COL_NAME],
            ProductRelationQueryContainer::COL_PRICE_PRODUCT => $this->formatProductPrice($item[SpyProductAbstractTableMap::COL_SKU]),
            ProductRelationQueryContainer::COL_ASSIGNED_CATEGORIES => $item[ProductRelationQueryContainer::COL_ASSIGNED_CATEGORIES],
            static::COL_STATUS => $this->getStatusLabel($item),
        ];

        if ($this->idProductRelation === null) {
            $results[static::COL_ACTIONS] = $this->buildSelectButton($item);
        }

        return $results;
    }

    /**
     * @param string $sku
     *
     * @return string
     */
    protected function formatProductPrice($sku)
    {
        $price = $this->priceProductFacade->findPriceBySku($sku);

        if ($price === null) {
            return 'N/A';
        }

        $moneyTransfer = $this->moneyFacade->fromInteger($price);

        return $this->moneyFacade->formatWithSymbol($moneyTransfer);
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function buildSelectButton(array $item)
    {
        return $this->generateViewButton(
            '#',
            'Select',
            [
                'data-select-product' => $item[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT],
                'data-row' => htmlentities($this->utilEncodingService->encodeJson($item), ENT_QUOTES, 'UTF-8'),
                'id' => 'select-product-' . $item[SpyProductAbstractTableMap::COL_SKU],
            ]
        );
    }
}
