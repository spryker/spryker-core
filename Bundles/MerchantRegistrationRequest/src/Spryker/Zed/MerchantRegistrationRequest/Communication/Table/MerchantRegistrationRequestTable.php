<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Communication\Table;

use Orm\Zed\MerchantRegistrationRequest\Persistence\Map\SpyMerchantRegistrationRequestTableMap;
use Orm\Zed\MerchantRegistrationRequest\Persistence\SpyMerchantRegistrationRequestQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Service\MerchantRegistrationRequestGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestConfig;

class MerchantRegistrationRequestTable extends AbstractTable
{
    /**
     * @var string
     */
    public const PARAM_ID_MERCHANT_REGISTRATION_REQUEST = 'id-merchant-registration-request';

    /**
     * @var string
     */
    protected const COL_ACTIONS = 'actions';

    /**
     * @var string
     */
    protected const COL_FULL_NAME = 'full_name';

    /**
     * @uses \Spryker\Zed\MerchantRegistrationRequest\Communication\Controller\ListController::tableDataAction()
     *
     * @var string
     */
    protected const URL_TABLE_DATA = '/table-data';

    /**
     * @uses \Spryker\Zed\MerchantRegistrationRequest\Communication\Controller\ViewController::indexAction()
     *
     * @var string
     */
    protected const URL_VIEW_MERCHANT_REGISTRATION_REQUEST = '/merchant-registration-request/view';

    /**
     * @var string
     */
    protected const STATUS_LABEL_DEFAULT = 'label';

    public function __construct(
        protected SpyMerchantRegistrationRequestQuery $merchantRegistrationRequestQuery,
        protected MerchantRegistrationRequestGuiToUtilDateTimeServiceInterface $utilDateTimeService,
        protected MerchantRegistrationRequestConfig $merchantRegistrationRequestConfig
    ) {
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = $this->setHeader($config);
        $config = $this->setSortable($config);
        $config = $this->setSearchable($config);
        $config = $this->setRawColumns($config);
        $config->setUrl($this->getTableUrl());

        $config->setDefaultSortField(SpyMerchantRegistrationRequestTableMap::COL_CREATED_AT, TableConfiguration::SORT_DESC);

        return $config;
    }

    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            SpyMerchantRegistrationRequestTableMap::COL_ID_MERCHANT_REGISTRATION_REQUEST => 'ID',
            SpyMerchantRegistrationRequestTableMap::COL_CREATED_AT => 'Created',
            SpyMerchantRegistrationRequestTableMap::COL_COMPANY_NAME => 'Merchant',
            static::COL_FULL_NAME => 'Full Name',
            SpyMerchantRegistrationRequestTableMap::COL_EMAIL => 'Email',
            SpyMerchantRegistrationRequestTableMap::COL_STATUS => 'Status',
            static::COL_ACTIONS => 'Actions',
        ]);

        return $config;
    }

    protected function setSortable(TableConfiguration $config): TableConfiguration
    {
        $config->setSortable([
            SpyMerchantRegistrationRequestTableMap::COL_ID_MERCHANT_REGISTRATION_REQUEST,
            SpyMerchantRegistrationRequestTableMap::COL_COMPANY_NAME,
            SpyMerchantRegistrationRequestTableMap::COL_EMAIL,
            SpyMerchantRegistrationRequestTableMap::COL_CREATED_AT,
            SpyMerchantRegistrationRequestTableMap::COL_STATUS,
        ]);

        return $config;
    }

    protected function setSearchable(TableConfiguration $config): TableConfiguration
    {
        $config->setSearchable([
            SpyMerchantRegistrationRequestTableMap::COL_COMPANY_NAME,
            SpyMerchantRegistrationRequestTableMap::COL_EMAIL,
            SpyMerchantRegistrationRequestTableMap::COL_STATUS,
        ]);

        return $config;
    }

    protected function setRawColumns(TableConfiguration $config): TableConfiguration
    {
        $config->setRawColumns([
            SpyMerchantRegistrationRequestTableMap::COL_STATUS,
            static::COL_ACTIONS,
        ]);

        return $config;
    }

    protected function getTableUrl(): string
    {
        return Url::generate(
            static::URL_TABLE_DATA,
            $this->getRequest()->query->all(),
        );
    }

    /**
     * @return array<mixed>
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->prepareQuery(), $config);
        $rows = [];

        foreach ($queryResults as $item) {
            $rows[] = $this->getRowData($item);
        }

        return $rows;
    }

    /**
     * @param array<string, mixed> $item
     *
     * @return array<string, mixed>
     */
    protected function getRowData(array $item): array
    {
        return [
            SpyMerchantRegistrationRequestTableMap::COL_ID_MERCHANT_REGISTRATION_REQUEST => $item[SpyMerchantRegistrationRequestTableMap::COL_ID_MERCHANT_REGISTRATION_REQUEST],
            SpyMerchantRegistrationRequestTableMap::COL_CREATED_AT => $this->utilDateTimeService
                ->formatDateTime($item[SpyMerchantRegistrationRequestTableMap::COL_CREATED_AT]),
            SpyMerchantRegistrationRequestTableMap::COL_COMPANY_NAME => $item[SpyMerchantRegistrationRequestTableMap::COL_COMPANY_NAME],
            static::COL_FULL_NAME => $item[SpyMerchantRegistrationRequestTableMap::COL_CONTACT_PERSON_FIRST_NAME] . ' ' . $item[SpyMerchantRegistrationRequestTableMap::COL_CONTACT_PERSON_LAST_NAME],
            SpyMerchantRegistrationRequestTableMap::COL_EMAIL => $item[SpyMerchantRegistrationRequestTableMap::COL_EMAIL],
            SpyMerchantRegistrationRequestTableMap::COL_STATUS => $this->getStatusLabel($item[SpyMerchantRegistrationRequestTableMap::COL_STATUS]),
            static::COL_ACTIONS => $this->getActionsColumnData($item),
        ];
    }

    /**
     * @module Store
     * @module Country
     */
    protected function prepareQuery(): SpyMerchantRegistrationRequestQuery
    {
        return $this->merchantRegistrationRequestQuery;
    }

    /**
     * @param array<string, mixed> $item
     */
    protected function getActionsColumnData(array $item): string
    {
        $buttons = [
            $this->generateViewButton(
                Url::generate(static::URL_VIEW_MERCHANT_REGISTRATION_REQUEST, [
                    static::PARAM_ID_MERCHANT_REGISTRATION_REQUEST => $item[SpyMerchantRegistrationRequestTableMap::COL_ID_MERCHANT_REGISTRATION_REQUEST],
                ]),
                'View',
            ),
        ];

        return implode(' ', $buttons);
    }

    protected function getStatusLabel(string $status): string
    {
        $class = $this->merchantRegistrationRequestConfig->getStatusClassLabelMapping()[$status] ?? static::STATUS_LABEL_DEFAULT;

        return $this->generateLabel(ucfirst($status), $class);
    }
}
