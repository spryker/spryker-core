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

class ProductTable extends AbstractTable
{

    const TABLE_IDENTIFIER = 'product-table';
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
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $idProductOptionGroup
     */
    public function __construct(
        ProductOptionQueryContainerInterface $productOptionQueryContainer,
        LocaleTransfer $localeTransfer,
        $idProductOptionGroup = null
    ) {
        $this->productOptionQueryContainer = $productOptionQueryContainer;
        $this->idProductOptionGroup = (int)$idProductOptionGroup;
        $this->defaultUrl = sprintf(
            'product-table?%s=%d',
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
        $query = $this->productOptionQueryContainer->queryProductsAbstractBySearchTerm(null, $this->localeTransfer);
        $query->setModelAlias('spy_product_abstract');

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $product) {
            $info = [
                'id' => $product[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT],
                'sku' => $product[SpyProductAbstractTableMap::COL_SKU],
                'name' => urlencode($product['name']),
            ];

            $checkbox_html = sprintf(
                "<input id='all_products_checkbox_%d' class='all-products-checkbox' type='checkbox' data-info='%s'>",
                $product[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT],
                Json::encode($info)
            );

            $results[] = [
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => $product[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT],
                SpyProductAbstractTableMap::COL_SKU => $product[SpyProductAbstractTableMap::COL_SKU],
                SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => $product['name'],
                self::COL_CHECKBOX => $checkbox_html,
            ];
        }
        unset($queryResults);

        return $results;
    }

}
