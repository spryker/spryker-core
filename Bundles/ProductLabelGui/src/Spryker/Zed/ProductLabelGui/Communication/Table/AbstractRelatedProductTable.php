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
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToPriceProductFacadeInterface;

abstract class AbstractRelatedProductTable extends AbstractTable
{
    public const PARAM_ID_PRODUCT_LABEL = 'id-product-label';
    public const COL_PRODUCT_ABSTRACT_NAME = SpyProductAbstractLocalizedAttributesTableMap::COL_NAME;
    public const COL_PRODUCT_ABSTRACT_CATEGORIES = RelatedProductTableQueryBuilder::RESULT_FIELD_PRODUCT_ABSTRACT_CATEGORY_NAMES_CSV;
    public const COL_PRODUCT_ABSTRACT_PRICE = RelatedProductTableQueryBuilder::RESULT_FIELD_PRODUCT_ABSTRACT_PRICE;
    public const COL_PRODUCT_ABSTRACT_STATUS = RelatedProductTableQueryBuilder::RESULT_FIELD_PRODUCT_CONCRETE_STATES_CSV;
    public const COL_SELECT_CHECKBOX = 'select-checkbox';

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
     * @var \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\ProductLabelGui\Communication\Table\RelatedProductTableQueryBuilderInterface $tableQueryBuilder
     * @param \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToMoneyInterface $moneyFacade
     * @param int|null $idProductLabel
     * @param \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(
        RelatedProductTableQueryBuilderInterface $tableQueryBuilder,
        ProductLabelGuiToMoneyInterface $moneyFacade,
        $idProductLabel,
        ProductLabelGuiToPriceProductFacadeInterface $priceProductFacade
    ) {
        $this->tableQueryBuilder = $tableQueryBuilder;
        $this->moneyFacade = $moneyFacade;
        $this->idProductLabel = $idProductLabel;
        $this->priceProductFacade = $priceProductFacade;
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
            static::COL_PRODUCT_ABSTRACT_NAME => $this->getNameColumn($productAbstractEntity),
            static::COL_PRODUCT_ABSTRACT_CATEGORIES => $this->getCategoriesColumn($productAbstractEntity),
            static::COL_PRODUCT_ABSTRACT_PRICE => $this->getPriceColumn($productAbstractEntity),
            static::COL_PRODUCT_ABSTRACT_STATUS => $this->getStatusColumn($productAbstractEntity),
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
            RelatedProductTableQueryBuilder::RESULT_FIELD_PRODUCT_ABSTRACT_NAME
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
            RelatedProductTableQueryBuilder::RESULT_FIELD_PRODUCT_ABSTRACT_CATEGORY_NAMES_CSV
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
        $price = $this->priceProductFacade->findPriceBySku($productAbstractEntity->getSku());

        if ($price === null) {
            return 'N/A';
        }

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
            RelatedProductTableQueryBuilder::RESULT_FIELD_PRODUCT_CONCRETE_STATES_CSV
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
