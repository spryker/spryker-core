<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Communication\Table;

use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\SspInquiryManagement\Persistence\Map\SpySspInquiryTableMap;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryQuery;
use Orm\Zed\StateMachine\Persistence\Map\SpyStateMachineItemStateTableMap;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig;

class SspInquiryTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const BUTTON_VIEW = 'View';

    /**
     * @var string
     */
    protected const PARAM_ID_SSP_INQUIRY = 'id-ssp-inquiry';

    /**
     * @var string
     */
    protected const URL_SSP_INQUIRY_DETAIL = '/ssp-inquiry-management/detail';

    /**
     * @var string
     */
    protected const COL_STATUS = 'status';

    /**
     * @var string
     */
    protected const COL_ACTIONS = 'actions';

    /**
     * @var string
     */
    protected const COL_FULL_NAME = 'full_name';

    /**
     * @var string
     */
    protected const CUSTOMER_FULL_NAME_FIELD_PATTERN = 'CONCAT(%s,\'%s\',%s)';

    /**
     * @param \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryQuery $sspInquiryQuery
     * @param \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig $sspInquiryManagementConfig
     * @param \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface $utilDateTimeService
     * @param \Generated\Shared\Transfer\SspInquiryConditionsTransfer $sspInquiryConditionsTransfer
     */
    public function __construct(
        protected SpySspInquiryQuery $sspInquiryQuery,
        protected SspInquiryManagementConfig $sspInquiryManagementConfig,
        protected UtilDateTimeServiceInterface $utilDateTimeService,
        protected SspInquiryConditionsTransfer $sspInquiryConditionsTransfer
    ) {
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $url = Url::generate(
            '/table',
            $this->getRequest()->query->all(),
        );
        $config->setUrl($url->build());

        $config = $this->setHeader($config);

        $config->setSortable([
            SpySspInquiryTableMap::COL_ID_SSP_INQUIRY,
            SpySspInquiryTableMap::COL_REFERENCE,
            SpySspInquiryTableMap::COL_TYPE,
            static::COL_FULL_NAME,
            SpySspInquiryTableMap::COL_CREATED_AT,
        ]);

        $config->setDefaultSortField(SpySspInquiryTableMap::COL_ID_SSP_INQUIRY, TableConfiguration::SORT_DESC);

        $config->setRawColumns([
            SpySspInquiryTableMap::COL_FK_STATE_MACHINE_ITEM_STATE,
            static::COL_ACTIONS,
        ]);

        $config->setSearchable([
            SpySspInquiryTableMap::COL_REFERENCE,
            $this->getCustomerFullNameFieldPattern(),
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $baseData = [
            SpySspInquiryTableMap::COL_ID_SSP_INQUIRY => 'ID',
            SpySspInquiryTableMap::COL_REFERENCE => 'Reference',
            SpySspInquiryTableMap::COL_TYPE => 'Type',
            static::COL_FULL_NAME => 'Customer',
            SpySspInquiryTableMap::COL_CREATED_AT => 'Date',
            SpySspInquiryTableMap::COL_FK_STATE_MACHINE_ITEM_STATE => 'Status',
            static::COL_ACTIONS => 'Actions',
        ];

        $config->setHeader($baseData);

        return $config;
    }

    /**
     * @return \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryQuery
     */
    protected function prepareQuery(): SpySspInquiryQuery
    {
        $this->sspInquiryQuery
            ->joinStateMachineItemState()
            ->useSpyCompanyUserQuery()
                ->joinCustomer()
            ->endUse()
            ->withColumn(SpyStateMachineItemStateTableMap::COL_NAME, static::COL_STATUS)
            ->withColumn(
                $this->getCustomerFullNameFieldPattern(),
                static::COL_FULL_NAME,
            );

        if ($this->sspInquiryConditionsTransfer->getType()) {
            $this->sspInquiryQuery->filterByType($this->sspInquiryConditionsTransfer->getType());
        }

        if ($this->sspInquiryConditionsTransfer->getStatus()) {
            $this->sspInquiryQuery->useStateMachineItemStateQuery()
                ->filterByName($this->sspInquiryConditionsTransfer->getStatus())
                ->endUse();
        }

        return $this->sspInquiryQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<mixed>
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->prepareQuery(), $config);
        $results = [];

        foreach ($queryResults as $item) {
            $results[] = $this->formatRow($item);
        }

        return $results;
    }

    /**
     * @param array<string, mixed> $item
     *
     * @return array<string, mixed>
     */
    protected function formatRow(array $item): array
    {
        /**
         * @var \Symfony\Contracts\Translation\TranslatorInterface $translator
         */
        $translator = $this->getTranslator();

        return [
            SpySspInquiryTableMap::COL_ID_SSP_INQUIRY => $item[SpySspInquiryTableMap::COL_ID_SSP_INQUIRY],
            SpySspInquiryTableMap::COL_REFERENCE => $item[SpySspInquiryTableMap::COL_REFERENCE],
            SpySspInquiryTableMap::COL_TYPE => $translator->trans($item[SpySspInquiryTableMap::COL_TYPE]),
            static::COL_FULL_NAME => $item[static::COL_FULL_NAME],
            SpySspInquiryTableMap::COL_CREATED_AT => $this->utilDateTimeService->formatDateTime($item[SpySspInquiryTableMap::COL_CREATED_AT]),
            SpySspInquiryTableMap::COL_FK_STATE_MACHINE_ITEM_STATE => $this->generateLabel(
                $item[static::COL_STATUS],
                $this->sspInquiryManagementConfig->getSspInquiryStatusClassMap()[$item[static::COL_STATUS]] ?? null,
            ),
            static::COL_ACTIONS => $this->buildLinks($item),
        ];
    }

    /**
     * @param array<string> $sspInquiry
     *
     * @return string
     */
    protected function buildLinks(array $sspInquiry): string
    {
        $buttons = [];

        $buttons[] = $this->generateViewButton(
            Url::generate(static::URL_SSP_INQUIRY_DETAIL, [static::PARAM_ID_SSP_INQUIRY => $sspInquiry[SpySspInquiryTableMap::COL_ID_SSP_INQUIRY]])->build(),
            static::BUTTON_VIEW,
        );

        return implode(' ', $buttons);
    }

    /**
     * @return string
     */
    protected function getCustomerFullNameFieldPattern(): string
    {
        return sprintf(
            static::CUSTOMER_FULL_NAME_FIELD_PATTERN,
            SpyCustomerTableMap::COL_FIRST_NAME,
            ' ',
            SpyCustomerTableMap::COL_LAST_NAME,
        );
    }
}
