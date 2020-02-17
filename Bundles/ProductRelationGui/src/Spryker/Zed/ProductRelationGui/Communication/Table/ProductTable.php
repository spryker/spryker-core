<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductRelationGui\Communication\Controller\ViewController;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductRelationGui\Dependency\QueryContainer\ProductRelationGuiToProductRelationQueryContainerInterface;
use Spryker\Zed\ProductRelationGui\Dependency\Service\ProductRelationGuiToUtilEncodingServiceInterface;

class ProductTable extends AbstractProductTable
{
    protected const COL_ACTIONS = 'Actions';
    protected const COL_STATUS = 'Status';
    protected const COL_ASSIGNED_CATEGORIES = 'assignedCategories';
    protected const COL_PRICE_PRODUCT = 'spy_price_product.price';

    /**
     * @var \Spryker\Zed\ProductRelationGui\Dependency\QueryContainer\ProductRelationGuiToProductRelationQueryContainerInterface $productRelationQueryContainer
     */
    protected $productRelationQueryContainer;

    /**
     * @var \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductRelationGui\Dependency\Service\ProductRelationGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var int|null
     */
    protected $idProductRelation;

    /**
     * @var \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\ProductRelationGui\Dependency\QueryContainer\ProductRelationGuiToProductRelationQueryContainerInterface $productRelationQueryContainer
     * @param \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductRelationGui\Dependency\Service\ProductRelationGuiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToMoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToPriceProductFacadeInterface $priceProductFacade
     * @param int|null $idProductRelation
     */
    public function __construct(
        ProductRelationGuiToProductRelationQueryContainerInterface $productRelationQueryContainer,
        ProductRelationGuiToLocaleFacadeInterface $localeFacade,
        ProductRelationGuiToUtilEncodingServiceInterface $utilEncodingService,
        ProductRelationGuiToMoneyFacadeInterface $moneyFacade,
        ProductRelationGuiToPriceProductFacadeInterface $priceProductFacade,
        ?int $idProductRelation = null
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
    protected function configure(TableConfiguration $config): TableConfiguration
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
    protected function setHeaders(TableConfiguration $config): void
    {
        $header = [
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => '#',
            SpyProductAbstractTableMap::COL_SKU => 'Sku',
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => 'Name',
            static::COL_PRICE_PRODUCT => 'Price',
            static::COL_ASSIGNED_CATEGORIES => 'Categories',
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
    protected function setRawColumns(TableConfiguration $config): void
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
    protected function setSortableFields(TableConfiguration $config): void
    {
        $config->setSortable([
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            SpyProductAbstractTableMap::COL_SKU,
            static::COL_PRICE_PRODUCT,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSearchableFields(TableConfiguration $config): void
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
    protected function setDefaultSortField(TableConfiguration $config): void
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
    protected function setTableUrl(TableConfiguration $config): void
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
    protected function prepareData(TableConfiguration $config): array
    {
        /** @var \Propel\Runtime\ActiveQuery\ModelCriteria $query */
        $query = $this->prepareQuery();

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $item) {
            $results[] = $this->mapResults($item);
        }

        return $results;
    }

    /**
     * @return \Propel\Runtime\ActiveQuery\Criteria
     */
    protected function prepareQuery(): Criteria
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();

        if ($this->idProductRelation !== null) {
            return $this->productRelationQueryContainer->queryProductsWithCategoriesRelationsByFkLocaleAndIdRelation(
                $localeTransfer->getIdLocale(),
                $this->idProductRelation
            );
        }

        return $this->productRelationQueryContainer
                ->queryProductsWithCategoriesByFkLocale($localeTransfer->getIdLocale());
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function mapResults(array $item): array
    {
        $results = [
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => $item[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT],
            SpyProductAbstractTableMap::COL_SKU => $item[SpyProductAbstractTableMap::COL_SKU],
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => $item[SpyProductAbstractLocalizedAttributesTableMap::COL_NAME],
            static::COL_PRICE_PRODUCT => $this->formatProductPrice($item[SpyProductAbstractTableMap::COL_SKU]),
            static::COL_ASSIGNED_CATEGORIES => $item[static::COL_ASSIGNED_CATEGORIES],
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
    protected function formatProductPrice(string $sku): string
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
    protected function buildSelectButton(array $item): string
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
