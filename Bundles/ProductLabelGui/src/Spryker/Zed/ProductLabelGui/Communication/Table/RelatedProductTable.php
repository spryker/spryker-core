<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToMoneyInterface;

class RelatedProductTable extends AbstractTable
{

    const PARAM_ID_PRODUCT_LABEL = 'id-product-label';
    const TABLE_IDENTIFIER = 'related-products-table';
    const COL_SELECT_CHECKBOX = 'select-checkbox';

    /**
     * @var \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\ProductLabelGui\Communication\Table\RelatedProductTableQueryBuilderInterface
     */
    protected $tableQueryBuilder;

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
        $idProductLabel = null
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
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);

        $config->setHeader([
            static::COL_SELECT_CHECKBOX => 'Select',
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
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->tableQueryBuilder->build($this->idProductLabel);

        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstract[] $abstractProductEntities */
        $abstractProductEntities = $this->runQuery($query, $config, true);

        $tableRows = [];

        foreach ($abstractProductEntities as $abstractProductEntity) {
            $tableRows[] = [
                static::COL_SELECT_CHECKBOX => $this->getSelectCheckboxColumn($abstractProductEntity),
                SpyProductAbstractTableMap::COL_SKU => $abstractProductEntity->getSku(),
                SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => $this->getNameColumn($abstractProductEntity),
                RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_PRICE => $this->getPriceColumn($abstractProductEntity),
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
        $hasRelation = $abstractProductEntity->getVirtualColumn(
            RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_LABEL_HAS_RELATION_FLAG
        );
        $checkedAttribute = $hasRelation ? 'checked="checked"' : '';

        return sprintf(
            '<input class="js-abstract-product-checkbox" type="checkbox" name="abstractProduct[]" value="%s" %s />',
            $abstractProductEntity->getIdProductAbstract(),
            $checkedAttribute
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
