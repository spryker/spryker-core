<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\ProductSet\Persistence\Map\SpyProductSetDataTableMap;
use Orm\Zed\ProductSet\Persistence\SpyProductSet;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductSetGui\Communication\Controller\DeleteController;
use Spryker\Zed\ProductSetGui\Communication\Controller\EditController;
use Spryker\Zed\ProductSetGui\Communication\Controller\ViewController;
use Spryker\Zed\ProductSetGui\Dependency\QueryContainer\ProductSetGuiToProductSetInterface;

class ProductSetTable extends AbstractTable
{

    const TABLE_IDENTIFIER = 'product-set-table';

    const COL_ID_PRODUCT_SET = 'id_product_set';
    const COL_NAME = 'name';
    const COL_PRODUCT_COUNT = 'product_count';
    const COL_IS_ACTIVE = 'is_active';
    const COL_ACTIONS = 'actions';

    /**
     * @var \Spryker\Zed\ProductSetGui\Dependency\QueryContainer\ProductSetGuiToProductSetInterface
     */
    protected $productSetGuiQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @param \Spryker\Zed\ProductSetGui\Dependency\QueryContainer\ProductSetGuiToProductSetInterface $productSetGuiQueryContainer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     */
    public function __construct(ProductSetGuiToProductSetInterface $productSetGuiQueryContainer, LocaleTransfer $localeTransfer)
    {
        $this->productSetGuiQueryContainer = $productSetGuiQueryContainer;
        $this->localeTransfer = $localeTransfer;

        $this->localeTransfer->requireIdLocale();
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->setTableIdentifier(self::TABLE_IDENTIFIER);

        $config->setHeader([
            self::COL_ID_PRODUCT_SET => 'ID',
            self::COL_NAME => 'Name',
            self::COL_PRODUCT_COUNT => '# of Products',
            self::COL_IS_ACTIVE => 'Status',
            self::COL_ACTIONS => 'Actions',
        ]);

        $config->setRawColumns([
            static::COL_IS_ACTIVE,
            static::COL_ACTIONS,
        ]);

        $config->setSearchable([
            self::COL_ID_PRODUCT_SET,
            self::COL_NAME,
        ]);

        $config->setSortable([
            static::COL_ID_PRODUCT_SET,
            static::COL_NAME,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->productSetGuiQueryContainer
            ->queryProductSet()
            ->useSpyProductSetDataQuery()
                ->filterByFkLocale($this->localeTransfer->getIdLocale())
            ->endUse()
            ->withColumn(SpyProductSetDataTableMap::COL_NAME, self::COL_NAME);

        $productSetCollection = $this->runQuery($query, $config, true);

        $tableData = [];
        foreach ($productSetCollection as $productSetEntity) {
            $tableData[] = $this->generateItem($productSetEntity);
        }

        return $tableData;
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return array
     */
    protected function generateItem(SpyProductSet $productSetEntity)
    {
        return [
            self::COL_ID_PRODUCT_SET => $productSetEntity->getIdProductSet(),
            self::COL_NAME => $productSetEntity->getVirtualColumn(self::COL_NAME),
            self::COL_PRODUCT_COUNT => $productSetEntity->countSpyProductAbstractSets(),
            self::COL_IS_ACTIVE => $this->getStatusLabel($productSetEntity->getIsActive()),
            self::COL_ACTIONS => $this->createActionButtons($productSetEntity),
        ];
    }

    /**
     * @param string $status
     *
     * @return string
     */
    protected function getStatusLabel($status)
    {
        if (!$status) {
            return '<span class="label label-danger">Inactive</span>';
        }

        return '<span class="label label-info">Active</span>';
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return string
     */
    protected function createActionButtons(SpyProductSet $productSetEntity)
    {
        $actions = [];

        $actions[] = $this->generateViewButton(
            Url::generate('/product-set-gui/view', [
                ViewController::PARAM_ID => $productSetEntity->getIdProductSet(),
            ]),
            'View'
        );

        $actions[] = $this->generateEditButton(
            Url::generate('/product-set-gui/edit', [
                EditController::PARAM_ID => $productSetEntity->getIdProductSet(),
            ]),
            'Edit'
        );

        $actions[] = $productSetEntity->getIsActive() ? $this->generateDeactivateButton($productSetEntity) : $this->generateActivateButton($productSetEntity);

        $actions[] = $this->generateRemoveButton(
            Url::generate('/product-set-gui/delete', [
                DeleteController::PARAM_ID => $productSetEntity->getIdProductSet(),
            ]),
            'Delete'
        );

        return implode(' ', $actions);
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return string
     */
    protected function generateActivateButton(SpyProductSet $productSetEntity)
    {
        return $this->generateViewButton(
            Url::generate('/product-set-gui/edit/activate', [
                EditController::PARAM_ID => $productSetEntity->getIdProductSet(),
            ]),
            'Activate'
        );
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return string
     */
    protected function generateDeactivateButton(SpyProductSet $productSetEntity)
    {
        return $this->generateRemoveButton(
            Url::generate('/product-set-gui/edit/deactivate', [
                EditController::PARAM_ID => $productSetEntity->getIdProductSet(),
            ]),
            'Deactivate'
        );
    }

}
