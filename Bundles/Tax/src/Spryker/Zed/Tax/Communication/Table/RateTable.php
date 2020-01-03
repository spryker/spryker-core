<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Table;

use Orm\Zed\Country\Persistence\Map\SpyCountryTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxRateTableMap;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxRateQuery;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class RateTable extends AbstractTable
{
    public const TABLE_COL_ACTIONS = 'Actions';
    public const URL_PARAM_ID_TAX_RATE = 'id-tax-rate';
    public const COUNTRY_NOT_AVAILABLE = 'N/A';

    /**
     * @var \Orm\Zed\Tax\Persistence\SpyTaxRateQuery
     */
    protected $taxRateQuery;

    /**
     * @var \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxRateQuery $taxRateQuery
     * @param \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface $utilDateTimeService
     */
    public function __construct(SpyTaxRateQuery $taxRateQuery, UtilDateTimeServiceInterface $utilDateTimeService)
    {
        $this->taxRateQuery = $taxRateQuery;
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
            SpyTaxRateTableMap::COL_ID_TAX_RATE => 'Tax rate ID',
            SpyTaxRateTableMap::COL_NAME => 'Name',
            SpyTaxRateTableMap::COL_CREATED_AT => 'Created at',
            SpyCountryTableMap::COL_NAME => 'Country',
            SpyTaxRateTableMap::COL_RATE => 'Percentage',
            self::TABLE_COL_ACTIONS => 'Actions',
        ]);

        $config->setSearchable([
            SpyTaxRateTableMap::COL_NAME,
            SpyCountryTableMap::COL_NAME,
        ]);

        $config->setSortable([
            SpyTaxRateTableMap::COL_ID_TAX_RATE,
            SpyCountryTableMap::COL_NAME,
            SpyTaxRateTableMap::COL_NAME,
            SpyTaxRateTableMap::COL_RATE,
            SpyTaxRateTableMap::COL_CREATED_AT,
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
        $query = $this->taxRateQuery
            ->leftJoinCountry(SpyCountryTableMap::TABLE_NAME);

        /** @var \Orm\Zed\Tax\Persistence\SpyTaxRate[] $queryResult */
        $queryResult = $this->runQuery($query, $config, true);

        foreach ($queryResult as $taxRateEntity) {
            $result[] = [
                SpyTaxRateTableMap::COL_ID_TAX_RATE => $taxRateEntity->getIdTaxRate(),
                SpyTaxRateTableMap::COL_CREATED_AT => $this->utilDateTimeService->formatDateTime($taxRateEntity->getCreatedAt()),
                SpyTaxRateTableMap::COL_NAME => $taxRateEntity->getName(),
                SpyCountryTableMap::COL_NAME => $this->getCountryName($taxRateEntity),
                SpyTaxRateTableMap::COL_RATE => $taxRateEntity->getRate(),
                self::TABLE_COL_ACTIONS => $this->getActionButtons($taxRateEntity),
            ];
        }

        return $result;
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxRate $taxRateEntity
     *
     * @return string
     */
    protected function getActionButtons(SpyTaxRate $taxRateEntity)
    {
        $buttons = [];
        $buttons[] = $this->createEditButton($taxRateEntity);
        $buttons[] = $this->createViewButton($taxRateEntity);
        $buttons[] = $this->createDeleteButton($taxRateEntity);

        return implode(' ', $buttons);
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxRate $taxRateEntity
     *
     * @return string
     */
    protected function createEditButton(SpyTaxRate $taxRateEntity)
    {
        $editTaxRateUrl = Url::generate(
            '/tax/rate/edit',
            [
                self::URL_PARAM_ID_TAX_RATE => $taxRateEntity->getIdTaxRate(),
            ]
        );

        return $this->generateEditButton($editTaxRateUrl, 'Edit');
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxRate $taxRateEntity
     *
     * @return string
     */
    protected function createViewButton(SpyTaxRate $taxRateEntity)
    {
        $viewTaxRateUrl = Url::generate(
            '/tax/rate/view',
            [
                self::URL_PARAM_ID_TAX_RATE => $taxRateEntity->getIdTaxRate(),
            ]
        );

        return $this->generateViewButton($viewTaxRateUrl, 'View');
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxRate $taxRateEntity
     *
     * @return string
     */
    protected function createDeleteButton(SpyTaxRate $taxRateEntity)
    {
        $deleteTaxRateUrl = Url::generate(
            '/tax/delete-rate',
            [
                self::URL_PARAM_ID_TAX_RATE => $taxRateEntity->getIdTaxRate(),
            ]
        );

        return $this->generateRemoveButton($deleteTaxRateUrl, 'Delete');
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxRate $taxRateEntity
     *
     * @return string
     */
    protected function getCountryName(SpyTaxRate $taxRateEntity)
    {
        $countryName = self::COUNTRY_NOT_AVAILABLE;

        /** @var \Orm\Zed\Country\Persistence\SpyCountry|null $countryEntity */
        $countryEntity = $taxRateEntity->getCountry();
        if ($countryEntity) {
            $countryName = $countryEntity->getName();
        }

        return $countryName;
    }
}
