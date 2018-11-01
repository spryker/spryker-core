<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Propel\Runtime\Formatter\SimpleArrayFormatter;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToProductInterface;
use Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface;

class ProductRuleTable extends AbstractProductTable
{
    public const COL_ACTION = 'action';
    public const COL_NAME = 'name';
    public const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    public const COL_SKU = 'sku';
    public const COL_CATEGORY_NAME = 'category_name';
    public const URL_PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    public const COL_STATUS = 'status';

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface
     */
    protected $productRelationQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var string
     */
    protected $yvesHostUrl;

    /**
     * @var \Generated\Shared\Transfer\ProductRelationTransfer
     */
    protected $productRelationTransfer;

    /**
     * @var string
     */
    protected $tableIdentifier = 'rule-query-table';

    /**
     * @var string
     */
    protected $tableUrlTemplate = '%s?data=%s';

    /**
     * @var bool
     */
    protected $showResultsWithoutCriteria = false;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToProductInterface $productFacade
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface $productRelationQueryContainer
     * @param \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface $utilEncodingService
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $yvesHostUrl
     */
    public function __construct(
        ProductRelationToProductInterface $productFacade,
        ProductRelationQueryContainerInterface $productRelationQueryContainer,
        ProductRelationToUtilEncodingInterface $utilEncodingService,
        ProductRelationTransfer $productRelationTransfer,
        LocaleTransfer $localeTransfer,
        $yvesHostUrl
    ) {

        $this->productFacade = $productFacade;
        $this->productRelationQueryContainer = $productRelationQueryContainer;
        $this->localeTransfer = $localeTransfer;
        $this->yvesHostUrl = $yvesHostUrl;
        $this->utilEncodingService = $utilEncodingService;

        $this->defaultUrl = $this->getDefaultUrl($productRelationTransfer);
        $this->productRelationTransfer = $productRelationTransfer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->setHeaders($config);
        $this->setSearchable($config);
        $this->setSortable($config);
        $this->addRawColumns($config);

        $config->setPageLength(10);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        if (!$this->showResultsWithoutCriteria && !$this->productRelationTransfer->getQuerySet()->getRules()->getArrayCopy()) {
            return [];
        }

        $query = $this->getQuery();
        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $data) {
            $results[] = $this->formatRow($data);
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function getQuery()
    {
        return $this->productRelationQueryContainer
            ->queryRulePropelQueryWithLocalizedProductData($this->productRelationTransfer)
            ->setFormatter(new SimpleArrayFormatter());
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function addRawColumns(TableConfiguration $config)
    {
        $config->addRawColumn(static::COL_ACTION)
            ->addRawColumn(static::COL_STATUS);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getRowData(array $data)
    {
        return [
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => $data[static::COL_ID_PRODUCT_ABSTRACT],
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => $data[static::COL_NAME],
            SpyProductAbstractTableMap::COL_SKU => $data[static::COL_SKU],
            static::COL_STATUS => $this->getStatusLabel($data),
            static::COL_CATEGORY_NAME => $data[static::COL_CATEGORY_NAME],
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getActions(array $data)
    {
        $action = $this->createViewProductAbstractButton($data);
        $action .= ' ' . $this->createViewInShopButton($data);

        return [
            static::COL_ACTION => $action,
        ];
    }

    /**
     * @param array $product
     *
     * @return string
     */
    protected function createViewProductAbstractButton(array $product)
    {
        $viewAbstractProductUrl = Url::generate(
            '/product-management/view',
            [
                static::URL_PARAM_ID_PRODUCT_ABSTRACT => $product[static::COL_ID_PRODUCT_ABSTRACT],
            ]
        );

        return $this->generateViewButton(
            $viewAbstractProductUrl,
            'View',
            ['target' => '_blank']
        );
    }

    /**
     * @param array $product
     *
     * @return string
     */
    protected function createViewInShopButton(array $product)
    {
        $url = $this->getProductUrl($product);
        if (!$url) {
            return '';
        }

        return $this->generateViewButton(
            $url,
            'View in Shop',
            ['target' => '_blank']
        );
    }

    /**
     * @param array $product
     *
     * @return string|null
     */
    protected function getProductUrl(array $product)
    {
        $productAbstract = (new ProductAbstractTransfer())
            ->setSku($product[static::COL_SKU])
            ->setIdProductAbstract($product[static::COL_ID_PRODUCT_ABSTRACT]);

        $url = null;
        $productUrl = $this->productFacade->getProductUrl($productAbstract);
        foreach ($productUrl->getUrls() as $localizedProductUrl) {
            if ($localizedProductUrl->getLocale()->getIdLocale() === $this->localeTransfer->getIdLocale()) {
                $url = $this->yvesHostUrl . $localizedProductUrl->getUrl();
                break;
            }
        }

        return $url;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function formatRow(array $data)
    {
        return $this->getRowData($data) + $this->getActions($data);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return string
     */
    protected function getDefaultUrl(ProductRelationTransfer $productRelationTransfer)
    {
        $json = $this->utilEncodingService->encodeJson(
            $productRelationTransfer->getQuerySet()->toArray()
        );

        return sprintf(
            $this->tableUrlTemplate,
            'rule-query-table',
            $json
        );
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setHeaders(TableConfiguration $config)
    {
        $config->setHeader([
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => 'ID',
            SpyProductAbstractTableMap::COL_SKU => 'SKU',
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => 'Name',
            static::COL_CATEGORY_NAME => 'Categories',
            static::COL_STATUS => 'Status',
            static::COL_ACTION => '',
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSearchable(TableConfiguration $config)
    {
        $config->setSearchable([
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
            SpyCategoryAttributeTableMap::COL_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSortable(TableConfiguration $config)
    {
        $config->setSortable([
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
            static::COL_CATEGORY_NAME,
        ]);
    }
}
