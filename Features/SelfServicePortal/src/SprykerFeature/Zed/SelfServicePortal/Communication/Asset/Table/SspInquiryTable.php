<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Table;

use Generated\Shared\Transfer\SspAssetTransfer;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspInquiryTableMap;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySspAssetQuery;
use Orm\Zed\StateMachine\Persistence\Map\SpyStateMachineItemStateTableMap;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class SspInquiryTable extends AbstractTable
{
    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ListAssetInquiryController::indexAction()
     *
     * @var string
     */
    protected const BASE_URL = '/self-service-portal/list-asset-inquiry';

    /**
     * @var string
     */
    public const PARAM_ID_SSP_ASSET = 'id-ssp-asset';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ViewInquiryController::indexAction()
     *
     * @var string
     */
    protected const URL_SSP_INQUIRY_DETAIL = '/self-service-portal/view-inquiry';

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
    protected const COL_ACTIONS = 'actions';

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySspAssetQuery $sspInquirySspAssetQuery
     * @param \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface $utilDateTimeService
     */
    public function __construct(
        protected SspAssetTransfer $sspAssetTransfer,
        protected SpySspInquirySspAssetQuery $sspInquirySspAssetQuery,
        protected UtilDateTimeServiceInterface $utilDateTimeService
    ) {
        $this->baseUrl = static::BASE_URL;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setUrl(sprintf('table?%s=%s', static::PARAM_ID_SSP_ASSET, $this->sspAssetTransfer->getIdSspAssetOrFail()));

        $config = $this->setHeader($config);

        $config->setSearchable([
            SpySspInquiryTableMap::COL_SUBJECT,
            SpySspInquiryTableMap::COL_REFERENCE,
        ]);

        $config->setRawColumns([
            SpySspInquiryTableMap::COL_SUBJECT,
            SpySspInquiryTableMap::COL_REFERENCE,
            SpyStateMachineItemStateTableMap::COL_NAME,
            static::COL_ACTIONS,
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
            SpySspInquiryTableMap::COL_REFERENCE => 'Reference',
            SpySspInquiryTableMap::COL_CREATED_AT => 'Date created',
            SpySspInquiryTableMap::COL_SUBJECT => 'Subject',
            SpyStateMachineItemStateTableMap::COL_NAME => 'Status',
            static::COL_ACTIONS => 'Action',
        ];

        $config->setHeader($baseData);

        return $config;
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySspAssetQuery
     */
    protected function prepareQuery(): SpySspInquirySspAssetQuery
    {
        $this->sspInquirySspAssetQuery
            ->filterByFkSspAsset($this->sspAssetTransfer->getIdSspAsset())
            ->joinWithSpySspInquiry()
            ->useSpySspInquiryQuery()
                ->joinWithStateMachineItemState()
            ->endUse()
            ->select([
                SpySspInquiryTableMap::COL_REFERENCE,
                SpySspInquiryTableMap::COL_SUBJECT,
                SpySspInquiryTableMap::COL_CREATED_AT,
                SpySspInquiryTableMap::COL_ID_SSP_INQUIRY,
                SpyStateMachineItemStateTableMap::COL_NAME,
            ]);

        return $this->sspInquirySspAssetQuery;
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
        $rowData = [
            SpySspInquiryTableMap::COL_REFERENCE => $item[SpySspInquiryTableMap::COL_REFERENCE],
            SpySspInquiryTableMap::COL_SUBJECT => $item[SpySspInquiryTableMap::COL_SUBJECT],
            SpyStateMachineItemStateTableMap::COL_NAME => $item[SpyStateMachineItemStateTableMap::COL_NAME],
            SpySspInquiryTableMap::COL_CREATED_AT => $this->utilDateTimeService->formatDateTime($item[SpySspInquiryTableMap::COL_CREATED_AT]),
            static::COL_ACTIONS => $this->buildLinks($item),
        ];

        return $rowData;
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
            Url::generate(static::URL_SSP_INQUIRY_DETAIL, [static::PARAM_ID_SSP_INQUIRY => $sspInquiry[SpySspInquiryTableMap::COL_ID_SSP_INQUIRY]]),
            static::BUTTON_VIEW,
        );

        return implode(' ', $buttons);
    }
}
