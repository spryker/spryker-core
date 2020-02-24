<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Table;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Formatter\SimpleArrayFormatter;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductFacadeInterface;
use Spryker\Zed\ProductRelationGui\Dependency\QueryContainer\ProductRelationGuiToProductRelationQueryContainerInterface;
use Spryker\Zed\ProductRelationGui\Dependency\Service\ProductRelationGuiToUtilEncodingServiceInterface;
use Spryker\Zed\ProductRelationGui\ProductRelationGuiConfig;

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
     * @var \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductRelationGui\Dependency\QueryContainer\ProductRelationGuiToProductRelationQueryContainerInterface $productRelationQueryContainer
     */
    protected $productRelationQueryContainer;

    /**
     * @var \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductRelationGui\ProductRelationGuiConfig
     */
    protected $productRelationGuiConfig;

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
     * @var \Spryker\Zed\ProductRelationGui\Dependency\Service\ProductRelationGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductRelationGui\Dependency\QueryContainer\ProductRelationGuiToProductRelationQueryContainerInterface $productRelationQueryContainer
     * @param \Spryker\Zed\ProductRelationGui\Dependency\Service\ProductRelationGuiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductRelationGui\ProductRelationGuiConfig $productRelationGuiConfig
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     */
    public function __construct(
        ProductRelationGuiToProductFacadeInterface $productFacade,
        ProductRelationGuiToProductRelationQueryContainerInterface $productRelationQueryContainer,
        ProductRelationGuiToUtilEncodingServiceInterface $utilEncodingService,
        ProductRelationGuiToLocaleFacadeInterface $localeFacade,
        ProductRelationGuiConfig $productRelationGuiConfig,
        ProductRelationTransfer $productRelationTransfer
    ) {

        $this->productFacade = $productFacade;
        $this->productRelationQueryContainer = $productRelationQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->productRelationGuiConfig = $productRelationGuiConfig;
        $this->utilEncodingService = $utilEncodingService;

        $this->defaultUrl = $this->getDefaultUrl($productRelationTransfer);
        $this->productRelationTransfer = $productRelationTransfer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
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
    protected function prepareData(TableConfiguration $config): array
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
    protected function getQuery(): ModelCriteria
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
    protected function addRawColumns(TableConfiguration $config): void
    {
        $config->addRawColumn(static::COL_ACTION)
            ->addRawColumn(static::COL_STATUS);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getRowData(array $data): array
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
    protected function getActions(array $data): array
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
    protected function createViewProductAbstractButton(array $product): string
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
    protected function createViewInShopButton(array $product): string
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
    protected function getProductUrl(array $product): ?string
    {
        $productAbstract = (new ProductAbstractTransfer())
            ->setSku($product[static::COL_SKU])
            ->setIdProductAbstract($product[static::COL_ID_PRODUCT_ABSTRACT]);

        $url = null;
        $productUrl = $this->productFacade->getProductUrl($productAbstract);
        $localeTransfer = $this->localeFacade->getCurrentLocale();
        foreach ($productUrl->getUrls() as $localizedProductUrl) {
            if ($localizedProductUrl->getLocale()->getIdLocale() === $localeTransfer->getIdLocale()) {
                $url = $this->productRelationGuiConfig->getYvesHost() . $localizedProductUrl->getUrl();

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
    protected function formatRow(array $data): array
    {
        return array_merge($this->getRowData($data), $this->getActions($data));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return string
     */
    protected function getDefaultUrl(ProductRelationTransfer $productRelationTransfer): string
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
    protected function setHeaders(TableConfiguration $config): void
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
    protected function setSearchable(TableConfiguration $config): void
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
    protected function setSortable(TableConfiguration $config): void
    {
        $config->setSortable([
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
            static::COL_CATEGORY_NAME,
        ]);
    }
}
