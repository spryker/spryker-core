<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Table;

use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class SetTable extends AbstractTable
{
    public const TABLE_COL_ACTIONS = 'Actions';
    public const URL_PARAM_ID_TAX_SET = 'id-tax-set';

    /**
     * @var \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    protected $taxSetQuery;

    /**
     * @var \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSetQuery $taxSetQuery
     * @param \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface $utilDateTimeService
     */
    public function __construct(SpyTaxSetQuery $taxSetQuery, UtilDateTimeServiceInterface $utilDateTimeService)
    {
        $this->taxSetQuery = $taxSetQuery;
        $this->utilDateTimeService = $utilDateTimeService;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $url = Url::generate('list-table')->build();

        $config->setUrl($url);
        $config->setHeader([
            SpyTaxSetTableMap::COL_ID_TAX_SET => 'Tax set ID',
            SpyTaxSetTableMap::COL_NAME => 'Name',
            SpyTaxSetTableMap::COL_CREATED_AT => 'Created at',
            self::TABLE_COL_ACTIONS => 'Actions',
        ]);

        $config->setSearchable([
            SpyTaxSetTableMap::COL_ID_TAX_SET,
            SpyTaxSetTableMap::COL_NAME,
        ]);

        $config->setSortable([
            SpyTaxSetTableMap::COL_ID_TAX_SET,
            SpyTaxSetTableMap::COL_NAME,
            SpyTaxSetTableMap::COL_CREATED_AT,
        ]);

        $config->setDefaultSortColumnIndex(0);
        $config->setDefaultSortDirection(TableConfiguration::SORT_DESC);
        $config->addRawColumn(self::TABLE_COL_ACTIONS);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $result = [];

        /** @var \Orm\Zed\Tax\Persistence\SpyTaxSet[] $queryResult */
        $queryResult = $this->runQuery($this->taxSetQuery, $config, true);

        foreach ($queryResult as $taxSetEntity) {
            $result[] = [
                SpyTaxSetTableMap::COL_ID_TAX_SET => $taxSetEntity->getIdTaxSet(),
                SpyTaxSetTableMap::COL_NAME => $taxSetEntity->getName(),
                SpyTaxSetTableMap::COL_CREATED_AT => $this->utilDateTimeService->formatDateTime($taxSetEntity->getCreatedAt()),
                self::TABLE_COL_ACTIONS => $this->getActionButtons($taxSetEntity),
            ];
        }

        return $result;
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSet $taxSetEntity
     *
     * @return string
     */
    protected function getActionButtons(SpyTaxSet $taxSetEntity)
    {
        $buttons = [];
        $buttons[] = $this->createEditButton($taxSetEntity);
        $buttons[] = $this->createViewButton($taxSetEntity);
        $buttons[] = $this->createDeleteButton($taxSetEntity);

        return implode(' ', $buttons);
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSet $taxRateEntity
     *
     * @return string
     */
    protected function createEditButton(SpyTaxSet $taxRateEntity)
    {
        $editTaxSetUrl = Url::generate(
            '/tax/set/edit',
            [
                self::URL_PARAM_ID_TAX_SET => $taxRateEntity->getIdTaxSet(),
            ]
        );

        return $this->generateEditButton($editTaxSetUrl, 'Edit');
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSet $taxSetEntity
     *
     * @return string
     */
    protected function createViewButton(SpyTaxSet $taxSetEntity)
    {
        $viewTaxSetUrl = Url::generate(
            '/tax/set/view',
            [
                self::URL_PARAM_ID_TAX_SET => $taxSetEntity->getIdTaxSet(),
            ]
        );

        return $this->generateViewButton($viewTaxSetUrl, 'View');
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSet $taxSetEntity
     *
     * @return string
     */
    protected function createDeleteButton(SpyTaxSet $taxSetEntity)
    {
        $deleteTaxSetUrl = Url::generate(
            '/tax/delete-set',
            [
                self::URL_PARAM_ID_TAX_SET => $taxSetEntity->getIdTaxSet(),
            ]
        );

        return $this->generateRemoveButton($deleteTaxSetUrl, 'Delete');
    }
}
