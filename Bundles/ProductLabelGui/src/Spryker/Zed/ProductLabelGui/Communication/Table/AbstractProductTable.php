<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToMoneyInterface;

abstract class AbstractProductTable extends AbstractTable
{

    const PARAM_ID_PRODUCT_LABEL = 'id-product-label';
    const COL_SELECT_CHECKBOX = 'select-checkbox';

    /**
     * @var \Spryker\Zed\ProductLabelGui\Communication\Table\RelatedProductTableQueryBuilderInterface
     */
    protected $tableQueryBuilder;

    /**
     * @var \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @var int|null
     */
    protected $idProductLabel;

    /**
     * @param \Spryker\Zed\ProductLabelGui\Communication\Table\RelatedProductTableQueryBuilderInterface $tableQueryBuilder
     * @param \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToMoneyInterface $moneyFacade
     * @param int|null $idProductLabel
     */
    public function __construct(
        RelatedProductTableQueryBuilderInterface $tableQueryBuilder,
        ProductLabelGuiToMoneyInterface $moneyFacade,
        $idProductLabel
    ) {
        $this->tableQueryBuilder = $tableQueryBuilder;
        $this->moneyFacade = $moneyFacade;
        $this->idProductLabel = $idProductLabel;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            static::COL_SELECT_CHECKBOX => 'Select',
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => 'ID',
            SpyProductAbstractTableMap::COL_SKU => 'SKU',
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => 'Name',
            RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_PRICE => 'Price',
        ]);

        $config->setRawColumns([
            static::COL_SELECT_CHECKBOX,
        ]);

        $config->setDefaultSortField(
            SpyProductAbstractTableMap::COL_SKU,
            TableConfiguration::SORT_ASC
        );

        $config->setSearchable([
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
        ]);

        $config->setUrl(sprintf(
            '%s?%s=%s',
            $this->defaultUrl,
            static::PARAM_ID_PRODUCT_LABEL,
            (int)$this->idProductLabel
        ));

        return $config;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract[]|\Propel\Runtime\Collection\ObjectCollection $productAbstractEntities
     *
     * @return array
     */
    protected function buildResultData(ObjectCollection $productAbstractEntities)
    {
        $tableRows = [];

        foreach ($productAbstractEntities as $productAbstractEntity) {
            $tableRows[] = [
                static::COL_SELECT_CHECKBOX => $this->getSelectCheckboxColumn($productAbstractEntity),
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT => $productAbstractEntity->getIdProductAbstract(),
                SpyProductAbstractTableMap::COL_SKU => $productAbstractEntity->getSku(),
                SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => $this->getNameColumn($productAbstractEntity),
                RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_PRICE => $this->getPriceColumn($productAbstractEntity),
            ];
        }

        return $tableRows;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $abstractProductEntity
     *
     * @return string
     */
    protected function getSelectCheckboxColumn(SpyProductAbstract $abstractProductEntity)
    {
        return sprintf(
            '<input class="%s" type="checkbox" name="abstractProduct[]" value="%s" data-info="%s"/>',
            'js-abstract-product-checkbox',
            $abstractProductEntity->getIdProductAbstract(),
            htmlspecialchars(json_encode([
                'id' => $abstractProductEntity->getIdProductAbstract(),
                'sku' => $abstractProductEntity->getSku(),
                'name' => $this->getNameColumn($abstractProductEntity),
            ]))
        );
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $abstractProductEntity
     *
     * @return string
     */
    protected function getNameColumn(SpyProductAbstract $abstractProductEntity)
    {
        return $abstractProductEntity->getVirtualColumn(
            RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_NAME
        );
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $abstractProductEntity
     *
     * @return string
     */
    protected function getPriceColumn(SpyProductAbstract $abstractProductEntity)
    {
        $price = (int)$abstractProductEntity->getVirtualColumn(
            RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_PRICE
        );
        $moneyTransfer = $this->moneyFacade->fromInteger($price);

        return $this->moneyFacade->formatWithSymbol($moneyTransfer);
    }

}
