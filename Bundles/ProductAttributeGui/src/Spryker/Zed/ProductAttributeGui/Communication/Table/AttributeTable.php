<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\ProductAttribute\Persistence\Map\SpyProductManagementAttributeTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface;

class AttributeTable extends AbstractTable
{
    public const COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE = 'id_product_management_attribute';
    public const COL_INPUT_TYPE = 'input_type';
    public const COL_ACTIONS = 'actions';

    /**
     * @var \Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface
     */
    protected $productAttributeQueryContainer;

    /**
     * @param \Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface $productAttributeQueryContainer
     */
    public function __construct(ProductAttributeGuiToProductAttributeQueryContainerInterface $productAttributeQueryContainer)
    {
        $this->productAttributeQueryContainer = $productAttributeQueryContainer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return mixed
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            static::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE => 'Attribute ID',
            SpyProductAttributeKeyTableMap::COL_KEY => 'Attribute Key',
            SpyProductAttributeKeyTableMap::COL_IS_SUPER => 'Super Attribute',
            static::COL_INPUT_TYPE => 'Type',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
        ]);

        $config->setSearchable([
            SpyProductAttributeKeyTableMap::COL_KEY,
            SpyProductManagementAttributeTableMap::COL_INPUT_TYPE,
        ]);

        $config->setSortable([
            static::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE,
            SpyProductAttributeKeyTableMap::COL_KEY,
            SpyProductAttributeKeyTableMap::COL_IS_SUPER,
            static::COL_INPUT_TYPE,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return mixed
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this
            ->productAttributeQueryContainer
            ->queryProductAttributeKey()
            ->joinSpyProductManagementAttribute()
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE, static::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_INPUT_TYPE, static::COL_INPUT_TYPE);

        $queryResults = $this->runQuery($query, $config);

        $productAbstractCollection = [];
        foreach ($queryResults as $item) {
            $productAbstractCollection[] = [
                static::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE => $item[static::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE],
                SpyProductAttributeKeyTableMap::COL_KEY => $item[SpyProductAttributeKeyTableMap::COL_KEY],
                SpyProductAttributeKeyTableMap::COL_IS_SUPER => $item[SpyProductAttributeKeyTableMap::COL_IS_SUPER],
                static::COL_INPUT_TYPE => $item[static::COL_INPUT_TYPE],
                static::COL_ACTIONS => implode(' ', $this->createActionColumn($item)),
            ];
        }

        return $productAbstractCollection;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function createActionColumn(array $item)
    {
        $urls = [];

        $urls[] = $this->generateViewButton(
            Url::generate('/product-attribute-gui/attribute/view', [
                'id' => $item[static::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE],
            ]),
            'View'
        );

        $urls[] = $this->generateEditButton(
            Url::generate('/product-attribute-gui/attribute/edit', [
                'id' => $item[static::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE],
            ]),
            'Edit'
        );

        return $urls;
    }
}
