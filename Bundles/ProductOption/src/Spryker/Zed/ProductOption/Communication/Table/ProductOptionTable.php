<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductOption\Dependency\Service\ProductOptionToUtilEncodingServiceInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;

class ProductOptionTable extends AbstractTable
{
    public const TABLE_IDENTIFIER = 'product-option-table';

    public const COL_CHECKBOX = 'checkbox';
    public const COL_ACTIONS = 'actions';

    public const TABLE_CONTEXT_EDIT = 'edit';
    public const TABLE_CONTEXT_VIEW = 'view';

    public const URL_PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';

    /**
     * @var \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected $productOptionQueryContainer;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Service\ProductOptionToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var int
     */
    protected $idProductOptionGroup;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var string
     */
    protected $tableContext;

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $productOptionQueryContainer
     * @param \Spryker\Zed\ProductOption\Dependency\Service\ProductOptionToUtilEncodingServiceInterface $utilEncodingService
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $idProductOptionGroup
     * @param string $tableContext
     */
    public function __construct(
        ProductOptionQueryContainerInterface $productOptionQueryContainer,
        ProductOptionToUtilEncodingServiceInterface $utilEncodingService,
        LocaleTransfer $localeTransfer,
        $idProductOptionGroup,
        $tableContext
    ) {
        $this->productOptionQueryContainer = $productOptionQueryContainer;
        $this->utilEncodingService = $utilEncodingService;
        $this->idProductOptionGroup = $idProductOptionGroup;

        $this->setTableIdentifier(self::TABLE_IDENTIFIER);
        $this->localeTransfer = $localeTransfer;
        $this->tableContext = $tableContext;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $url = Url::generate(
            'product-option-table',
            [
                'id-product-option-group' => $this->idProductOptionGroup,
                'table-context' => $this->tableContext,
            ]
        )->build();

        $config->setUrl($url);

        $config->setHeader(array_merge([
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => 'ID',
            SpyProductAbstractTableMap::COL_SKU => 'SKU',
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => 'Name',
        ], $this->buildHeaderActionByTableContext()));

        $config->setSearchable([
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
        ]);

        $config->addRawColumn(self::COL_CHECKBOX);
        $config->addRawColumn(self::COL_ACTIONS);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->productOptionQueryContainer
            ->queryAbstractProductsByOptionGroupId($this->idProductOptionGroup, $this->localeTransfer);

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $productOption) {
            $results[] = array_merge([
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => $productOption['id_product_abstract'],
                SpyProductAbstractTableMap::COL_SKU => $productOption['sku'],
                SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => $productOption['name'],
            ], $this->buildActionByTableContext($productOption));
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param array $productOption
     *
     * @return array
     */
    protected function buildActionByTableContext(array $productOption)
    {
        if ($this->tableContext === self::TABLE_CONTEXT_EDIT) {
            return [
                self::COL_CHECKBOX => $this->getCheckboxHtml($productOption),
            ];
        }

        return [
            self::COL_ACTIONS => $this->createViewButton($productOption['id_product_abstract']),
        ];
    }

    /**
     * @return array
     */
    protected function buildHeaderActionByTableContext()
    {
        if ($this->tableContext === self::TABLE_CONTEXT_EDIT) {
            return [
               self::COL_CHECKBOX => 'Selected',
            ];
        }

         return [
             self::COL_ACTIONS => 'Actions',
         ];
    }

    /**
     * @param int $idProductAbstract
     *
     * @return string
     */
    protected function createViewButton($idProductAbstract)
    {
        $viewProductOptionUrl = Url::generate(
            '/product/index/view',
            [
                self::URL_PARAM_ID_PRODUCT_ABSTRACT => $idProductAbstract,
            ]
        );

        return $this->generateViewButton($viewProductOptionUrl, 'View');
    }

    /**
     * @param array $productOption
     *
     * @return string
     */
    protected function getCheckboxHtml(array $productOption)
    {
        $info = [
            'id' => $productOption['id_product_abstract'],
            'sku' => $productOption['sku'],
            'name' => urlencode($productOption['name']),
        ];

        return sprintf(
            "<input id='product_category_checkbox_%d' class='product_category_checkbox' type='checkbox' checked='checked' data-info='%s'>",
            $productOption['id_product_abstract'],
            (string)$this->utilEncodingService->encodeJson($info)
        );
    }
}
