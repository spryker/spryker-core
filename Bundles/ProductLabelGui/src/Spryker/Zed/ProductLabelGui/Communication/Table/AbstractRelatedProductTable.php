<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToMoneyInterface;

abstract class AbstractRelatedProductTable extends AbstractTable
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
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->getQuery();

        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstract[] $productAbstractEntities */
        $productAbstractEntities = $this->runQuery($query, $config, true);

        $rows = [];
        foreach ($productAbstractEntities as $productAbstractEntity) {
            $rows[] = $this->getRow($productAbstractEntity);
        }

        return $rows;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    abstract protected function getQuery();

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return array
     */
    protected function getRow(SpyProductAbstract $productAbstractEntity)
    {
        return [
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => $this->getNameColumn($productAbstractEntity),
            RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_CATEGORY_NAMES_CSV => $this->getCategoriesColumn($productAbstractEntity),
            RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_PRICE => $this->getPriceColumn($productAbstractEntity),
            RelatedProductTableQueryBuilder::RESULT_FIELD_CONCRETE_PRODUCT_STATES_CSV => $this->getStatusColumn($productAbstractEntity),
        ];
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getNameColumn(SpyProductAbstract $productAbstractEntity)
    {
        return $productAbstractEntity->getVirtualColumn(
            RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_NAME
        );
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getCategoriesColumn(SpyProductAbstract $productAbstractEntity)
    {
        $categoriesCsv = $productAbstractEntity->getVirtualColumn(
            RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_CATEGORY_NAMES_CSV
        );
        $categories = explode(',', $categoriesCsv);
        $categories = array_unique($categories);

        return implode(', ', $categories);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getPriceColumn(SpyProductAbstract $productAbstractEntity)
    {
        $price = (int)$productAbstractEntity->getVirtualColumn(
            RelatedProductTableQueryBuilder::RESULT_FIELD_ABSTRACT_PRODUCT_PRICE
        );
        $moneyTransfer = $this->moneyFacade->fromInteger($price);

        return $this->moneyFacade->formatWithSymbol($moneyTransfer);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getStatusColumn(SpyProductAbstract $productAbstractEntity)
    {
        $statesCsv = $productAbstractEntity->getVirtualColumn(
            RelatedProductTableQueryBuilder::RESULT_FIELD_CONCRETE_PRODUCT_STATES_CSV
        );
        $states = explode(',', $statesCsv);
        $isActive = in_array('true', $states);

        $statusName = $isActive ? 'Active' : 'Inactive';
        $statusCssClass = $isActive ? 'label-info' : 'label-danger';

        return sprintf(
            '<span class="label %s">%s</span>',
            $statusCssClass,
            $statusName
        );
    }

}
