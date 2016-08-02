<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Spryker\Shared\Library\Json;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;

class ProductOptionTable extends AbstractTable
{

    const TABLE_IDENTIFIER = 'product-option-table';
    const COL_CHECKBOX = 'checkbox';

    /**
     * @var \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected $productOptionQueryContainer;

    /**
     * @var int
     */
    protected $idProductOptionGroup;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $productOptionQueryContainer
     * @param int $idProductOptionGroup
     */
    public function __construct(
        ProductOptionQueryContainerInterface $productOptionQueryContainer,
        LocaleTransfer $localeTransfer,
        $idProductOptionGroup
    ) {
        $this->productOptionQueryContainer = $productOptionQueryContainer;
        $this->idProductOptionGroup = $idProductOptionGroup;

        $this->defaultUrl = sprintf(
            'product-option-table?%s=%d',
            'id-product-option-group',
            $this->idProductOptionGroup
        );

        $this->setTableIdentifier(self::TABLE_IDENTIFIER);
        $this->localeTransfer = $localeTransfer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => 'ID',
            SpyProductAbstractTableMap::COL_SKU => 'SKU',
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => 'Name',
            self::COL_CHECKBOX => 'Selected',
        ]);
        $config->setSearchable([
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
        ]);

        $config->addRawColumn(self::COL_CHECKBOX);

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
        foreach ($queryResults as $productOptions) {
            $results[] = [
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => $productOptions['id_product_abstract'],
                SpyProductAbstractTableMap::COL_SKU => $productOptions['sku'],
                SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => $productOptions['name'],
                self::COL_CHECKBOX => $this->getCheckboxHtml($productOptions),
            ];
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param array $productCategory
     *
     * @return string
     */
    protected function getCheckboxHtml(array $productCategory)
    {
        $info = [
            'id' => $productCategory['id_product_abstract'],
            'sku' => $productCategory['sku'],
            'name' => urlencode($productCategory['name']),
        ];

        return sprintf(
            "<input id='product_category_checkbox_%d' class='product_category_checkbox' type='checkbox' checked='checked' data-info='%s'>",
            $productCategory['id_product_abstract'],
            Json::encode($info)
        );
    }

}
