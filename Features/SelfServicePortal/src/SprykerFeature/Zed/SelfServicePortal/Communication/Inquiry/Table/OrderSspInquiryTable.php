<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Table;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspInquiryTableMap;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryQuery;
use Orm\Zed\StateMachine\Persistence\Map\SpyStateMachineItemStateTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class OrderSspInquiryTable extends AbstractTable
{
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
    public const PARAM_ID_ORDER = 'id-sales-order';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ListOrderInquiryController::indexAction()
     *
     * @var string
     */
    protected const BASE_URL = '/self-service-portal/list-order-inquiry';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ViewInquiryController::indexAction()
     *
     * @var string
     */
    protected const URL_VIEW_INQUIRY = '/self-service-portal/view-inquiry';

    /**
     * @var string
     */
    protected const PARAM_ID_SSP_INQUIRY = 'id-ssp-inquiry';

    /**
     * @var string
     */
    protected const BUTTON_VIEW = 'View';

    public function __construct(
        protected SpySspInquiryQuery $sspInquiryQuery,
        protected SelfServicePortalConfig $selfServicePortalConfig,
        protected OrderTransfer $orderTransfer
    ) {
        $this->baseUrl = static::BASE_URL;
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setUrl(sprintf('table?%s=%s', static::PARAM_ID_ORDER, $this->orderTransfer->getIdSalesOrder()));

        $config = $this->setHeader($config);

        $config->setRawColumns([
            SpySspInquiryTableMap::COL_FK_STATE_MACHINE_ITEM_STATE,
            SpySspInquiryTableMap::COL_SUBJECT,
            static::COL_ACTIONS,
        ]);

        return $config;
    }

    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $baseData = [
            SpySspInquiryTableMap::COL_REFERENCE => 'Reference',
            SpySspInquiryTableMap::COL_SUBJECT => 'Subject',
            SpySspInquiryTableMap::COL_FK_STATE_MACHINE_ITEM_STATE => 'Status',
            static::COL_ACTIONS => 'Actions',
        ];

        $config->setHeader($baseData);

        return $config;
    }

    protected function prepareQuery(): SpySspInquiryQuery
    {
        $this->sspInquiryQuery
            ->joinStateMachineItemState()
            ->withColumn(SpyStateMachineItemStateTableMap::COL_NAME, static::COL_STATUS)
            ->useSpySspInquirySalesOrderQuery()
                ->filterByFkSalesOrder($this->orderTransfer->getIdSalesOrder())
            ->endUse();

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
        $rowData = [
            SpySspInquiryTableMap::COL_REFERENCE => $item[SpySspInquiryTableMap::COL_REFERENCE],
            SpySspInquiryTableMap::COL_SUBJECT => $item[SpySspInquiryTableMap::COL_SUBJECT],
            SpySspInquiryTableMap::COL_FK_STATE_MACHINE_ITEM_STATE => $this->generateLabel(
                $item[static::COL_STATUS] ?? '',
                $this->selfServicePortalConfig->getInquiryStatusClassMap()[$item[static::COL_STATUS]] ?? '',
            ),
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
            Url::generate(static::URL_VIEW_INQUIRY, [static::PARAM_ID_SSP_INQUIRY => $sspInquiry[SpySspInquiryTableMap::COL_ID_SSP_INQUIRY]]),
            static::BUTTON_VIEW,
        );

        return implode(' ', $buttons);
    }
}
