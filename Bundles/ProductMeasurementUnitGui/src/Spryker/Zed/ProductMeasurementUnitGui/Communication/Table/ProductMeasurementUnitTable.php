<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitGui\Communication\Table;

use Orm\Zed\ProductMeasurementUnit\Persistence\Map\SpyProductMeasurementUnitTableMap;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnit;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnitQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductMeasurementUnitGui\Dependency\Facade\ProductMeasurementUnitGuiToProductMeasurementUnitFacadeInterface;

class ProductMeasurementUnitTable extends AbstractTable
{
    /**
     * @uses \Spryker\Zed\ProductMeasurementUnitGui\Communication\Controller\IndexController::REQUEST_PARAM_CODE
     *
     * @var string
     */
    protected const REQUEST_PARAM_CODE = 'code';

    /**
     * @var string
     */
    protected const COL_ID_PRODUCT_MEASUREMENT_UNIT = 'id_product_measurement_unit';

    /**
     * @var string
     */
    protected const COL_CODE = 'code';

    /**
     * @var string
     */
    protected const COL_NAME = 'name';

    /**
     * @var string
     */
    protected const COL_DEFAULT_PRECISION = 'default_precision';

    /**
     * @var string
     */
    protected const COL_ACTIONS = 'actions';

    /**
     * @uses \Spryker\Zed\ProductMeasurementUnitGui\Communication\Controller\IndexController::editAction()
     *
     * @var string
     */
    protected const ACTION_EDIT = '/product-measurement-unit-gui/index/edit';

    /**
     * @uses \Spryker\Zed\ProductMeasurementUnitGui\Communication\Controller\IndexController::deleteAction()
     *
     * @var string
     */
    protected const ACTION_DELETE = '/product-measurement-unit-gui/index/delete';

    /**
     * @var string
     */
    protected const ACTION_LABEL_EDIT = 'Edit';

    /**
     * @var string
     */
    protected const ACTION_LABEL_DELETE = 'Delete';

    /**
     * @var string
     */
    protected const COLUMN_LABEL_ID = 'ID';

    /**
     * @var string
     */
    protected const COLUMN_LABEL_CODE = 'Code';

    /**
     * @var string
     */
    protected const COLUMN_LABEL_NAME = 'Name';

    /**
     * @var string
     */
    protected const COLUMN_LABEL_DEFAULT_PRECISION = 'Default Precision';

    /**
     * @var string
     */
    protected const COLUMN_LABEL_ACTIONS = 'Actions';

    /**
     * @param \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnitQuery $productMeasurementUnitQuery
     * @param \Spryker\Zed\ProductMeasurementUnitGui\Dependency\Facade\ProductMeasurementUnitGuiToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade
     */
    public function __construct(
        protected SpyProductMeasurementUnitQuery $productMeasurementUnitQuery,
        protected ProductMeasurementUnitGuiToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade
    ) {
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_ID_PRODUCT_MEASUREMENT_UNIT => static::COLUMN_LABEL_ID,
            static::COL_CODE => static::COLUMN_LABEL_CODE,
            static::COL_NAME => static::COLUMN_LABEL_NAME,
            static::COL_DEFAULT_PRECISION => static::COLUMN_LABEL_DEFAULT_PRECISION,
            static::COL_ACTIONS => static::COLUMN_LABEL_ACTIONS,
        ]);

        $config->setSortable([
            static::COL_ID_PRODUCT_MEASUREMENT_UNIT,
            static::COL_CODE,
            static::COL_NAME,
        ]);

        $config->setSearchable([
            SpyProductMeasurementUnitTableMap::COL_CODE,
            SpyProductMeasurementUnitTableMap::COL_NAME,
        ]);

        $config->setHasSearchableFieldsWithAggregateFunctions(true);

        $config->setRawColumns([
            static::COL_ACTIONS,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $productMeasurementUnitEntityCollection = $this->runQuery(
            $this->productMeasurementUnitQuery,
            $config,
            true,
        );

        if (count($productMeasurementUnitEntityCollection) === 0) {
            return [];
        }

        $productMeasurementUnitRows = [];
        foreach ($productMeasurementUnitEntityCollection as $productMeasurementUnitEntity) {
            $productMeasurementUnitRows[] = $this->mapSpyProductMeasurementUnit($productMeasurementUnitEntity);
        }

        return $productMeasurementUnitRows;
    }

    /**
     * @param \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnit $productMeasurementUnitEntity
     *
     * @return array<string>
     */
    protected function mapSpyProductMeasurementUnit(SpyProductMeasurementUnit $productMeasurementUnitEntity): array
    {
        $productMeasurementUnitRow = $productMeasurementUnitEntity->toArray();

        $productMeasurementUnitRow[static::COL_ID_PRODUCT_MEASUREMENT_UNIT] = $this->formatInt(
            $productMeasurementUnitRow[static::COL_ID_PRODUCT_MEASUREMENT_UNIT],
        );
        $productMeasurementUnitRow[static::COL_DEFAULT_PRECISION] = $this->formatInt(
            $productMeasurementUnitRow[static::COL_DEFAULT_PRECISION],
        );

        $buttons = [];
        $buttons[] = $this->generateEditButton(
            Url::generate(
                static::ACTION_EDIT,
                [static::REQUEST_PARAM_CODE => $productMeasurementUnitRow[static::COL_CODE]],
            ),
            static::ACTION_LABEL_EDIT,
        );
        $buttons[] = $this->generateRemoveButton(
            Url::generate(
                static::ACTION_DELETE,
                [static::REQUEST_PARAM_CODE => $productMeasurementUnitRow[static::COL_CODE]],
            ),
            static::ACTION_LABEL_DELETE,
        );

        $buttons = implode(' ', $buttons);
        $productMeasurementUnitRow[static::COL_ACTIONS] = $buttons;

        return $productMeasurementUnitRow;
    }
}
