<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetGui\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\ProductSet\Persistence\Map\SpyProductSetDataTableMap;
use Orm\Zed\ProductSet\Persistence\SpyProductSet;
use Orm\Zed\ProductSet\Persistence\SpyProductSetQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\ContentProductSetGui\Communication\Controller\ProductSetController;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ProductSetSelectedTable extends AbstractTable
{
    public const TABLE_IDENTIFIER = 'product-set-selected-table';
    public const TABLE_CLASS = 'product-set-selected-table gui-table-data';
    public const BASE_URL = '/content-product-set-gui/product-set/';

    public const COL_ALIAS_NAME = 'name';

    public const COL_ID_PRODUCT_SET = 'ID';
    public const COL_NAME = 'Name';
    public const COL_COUNT = 'Number of Products';
    public const COL_STATUS = 'Status';
    public const COL_ACTIONS = 'Actions';

    public const TITLE_BUTTON_DELETE = 'Delete';
    public const CLASS_BUTTON_DELETE = 'js-delete-product-set btn btn-sm btn-outline btn-danger';
    public const ICON_BUTTON_DELETE = 'fa-trash';

    /**
     * @var \Orm\Zed\ProductSet\Persistence\SpyProductSetQuery
     */
    protected $productSetQuery;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var string|null
     */
    protected $identifierSuffix;

    /**
     * @var int|null
     */
    protected $idProductSet;

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSetQuery $productSetQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string|null $identifierSuffix
     * @param int|null $idProductSet
     */
    public function __construct(
        SpyProductSetQuery $productSetQuery,
        LocaleTransfer $localeTransfer,
        ?string $identifierSuffix,
        ?int $idProductSet
    ) {
        $this->productSetQuery = $productSetQuery;
        $this->localeTransfer = $localeTransfer;
        $this->identifierSuffix = $identifierSuffix;
        $this->idProductSet = $idProductSet;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $parameters = [];

        if ($this->idProductSet) {
            $parameters = [ProductSetController::PARAM_ID => $this->idProductSet];
        }

        $this->baseUrl = static::BASE_URL;
        $this->defaultUrl = Url::generate(static::TABLE_IDENTIFIER, $parameters)->build();
        $this->tableClass = static::TABLE_CLASS;
        $this->setTableIdentifier(sprintf('%s-%s', static::TABLE_IDENTIFIER, $this->identifierSuffix));

        $this->disableSearch();

        $config->setHeader([
            static::COL_ID_PRODUCT_SET => static::COL_ID_PRODUCT_SET,
            static::COL_NAME => static::COL_NAME,
            static::COL_COUNT => static::COL_COUNT,
            static::COL_STATUS => static::COL_STATUS,
            static::COL_ACTIONS => static::COL_ACTIONS,
        ]);

        $config->setRawColumns([
            static::COL_STATUS,
            static::COL_ACTIONS,
        ]);

        $config->setStateSave(false);

        return $config;
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function newTableConfiguration(): TableConfiguration
    {
        $tableConfiguration = parent::newTableConfiguration();
        $tableConfiguration->setServerSide(false);
        $tableConfiguration->setPaging(false);
        $tableConfiguration->setOrdering(false);

        return $tableConfiguration;
    }

    /**
     * @module Product
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $productSetList = [];
        if ($this->idProductSet) {
            $query = $this->productSetQuery
                ->filterByIdProductSet($this->idProductSet)
                ->useSpyProductSetDataQuery()
                ->filterByFkLocale($this->localeTransfer->getIdLocale())
                ->endUse()
                ->withColumn(SpyProductSetDataTableMap::COL_NAME, static::COL_ALIAS_NAME);

            $queryResults = $this->runQuery($query, $config, true);

            /** @var \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity */
            foreach ($queryResults as $productSetEntity) {
                $productSetList[] = $this->formatRow($productSetEntity);
            }
        }

        return $productSetList;
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return array
     */
    protected function formatRow(SpyProductSet $productSetEntity): array
    {
        return [
            static::COL_ID_PRODUCT_SET => $productSetEntity->getIdProductSet(),
            static::COL_NAME => $productSetEntity->getVirtualColumn(static::COL_ALIAS_NAME),
            static::COL_COUNT => $productSetEntity->countSpyProductAbstractSets(),
            static::COL_STATUS => $this->getStatusLabel($productSetEntity),
            static::COL_ACTIONS => $this->getActionButtons($productSetEntity),
        ];
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return string
     */
    protected function getStatusLabel(SpyProductSet $productSetEntity): string
    {
        if (!$productSetEntity->getIsActive()) {
            return $this->generateLabel('Inactive', 'label-danger');
        }

        return $this->generateLabel('Active', 'label-info');
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return string
     */
    protected function getActionButtons(SpyProductSet $productSetEntity): string
    {
        $actionButtons = [];

        $actionButtons[] = $this->generateButton('#', static::TITLE_BUTTON_DELETE, [
            'class' => static::CLASS_BUTTON_DELETE,
            'data-id' => $productSetEntity->getIdProductSet(),
            'icon' => static::ICON_BUTTON_DELETE,
        ]);

        return implode(' ', $actionButtons);
    }
}
