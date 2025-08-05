<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Table;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesShipmentTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class SspServiceTable extends AbstractTable
{
    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ListAssetServiceController::indexAction()
     *
     * @var string
     */
    protected const BASE_URL = '/self-service-portal/list-asset-service';

    /**
     * @var string
     */
    protected const PARAM_SSP_ASSET_REFERENCE = 'ssp_asset_reference';

    /**
     * @var string
     */
    protected const URL_SALES_ORDER_DETAIL = '/sales/detail';

    /**
     * @var string
     */
    protected const BUTTON_VIEW = 'View';

    /**
     * @var string
     */
    protected const PARAM_ID_SALES_ORDER_ITEM = 'id-sales-order-item';

    /**
     * @var string
     */
    protected const PARAM_ID_SALES_ORDER = 'id-sales-order';

    /**
     * @var string
     */
    protected const COL_ACTIONS = 'actions';

    /**
     * @var string
     */
    protected const FIELD_ORDER_REFERENCE = 'order_reference';

    /**
     * @var string
     */
    protected const FIELD_FIRST_NAME = 'first_name';

    /**
     * @var string
     */
    protected const FIELD_LAST_NAME = 'last_name';

    /**
     * @var string
     */
    protected const FIELD_CUSTOMER = 'customer';

    /**
     * @var string
     */
    protected const FIELD_COMPANY_UUID = 'company_uuid';

    /**
     * @var string
     */
    protected const FIELD_COMPANY_NAME = 'company_name';

    /**
     * @var string
     */
    protected const FIELD_STATUS = 'status';

    /**
     * @var string
     */
    protected const FIELD_REQUESTED_DELIVERY_DATE = 'requested_delivery_date';

    public function __construct(
        protected string $assetReference,
        protected SpySalesOrderItemQuery $salesOrderItemQuery,
        protected UtilDateTimeServiceInterface $utilDateTimeService
    ) {
        $this->baseUrl = static::BASE_URL;
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setUrl(sprintf('table?%s=%s', static::PARAM_SSP_ASSET_REFERENCE, $this->assetReference));

        $config = $this->setHeader($config);

        $config->setSearchable([
            SpySalesOrderTableMap::COL_ORDER_REFERENCE,
            SpySalesOrderItemTableMap::COL_NAME,
        ]);

        $config->setRawColumns([
            SpySalesOrderTableMap::COL_ORDER_REFERENCE,
            SpySalesOrderItemTableMap::COL_NAME,
            static::FIELD_STATUS,
            static::COL_ACTIONS,
        ]);

        return $config;
    }

    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $baseData = [
            SpySalesOrderTableMap::COL_ORDER_REFERENCE => 'Order Reference',
            static::FIELD_CUSTOMER => 'Customer',
            static::FIELD_COMPANY_NAME => 'Company',
            SpySalesOrderItemTableMap::COL_NAME => 'Service (Product name)',
            static::FIELD_STATUS => 'Status',
            static::FIELD_REQUESTED_DELIVERY_DATE => 'Time a date of Service',
            SpySalesOrderItemTableMap::COL_CREATED_AT => 'Date created',
            static::COL_ACTIONS => 'Action',
        ];

        $config->setHeader($baseData);

        return $config;
    }

    protected function prepareQuery(): SpySalesOrderItemQuery
    {
        $query = $this->salesOrderItemQuery;

        $query
            ->joinWithOrder()
            ->joinWithState()
            ->joinWithSpySalesShipment()
            ->useSalesOrderItemSspAssetQuery()
                ->filterByReference($this->assetReference)
            ->endUse()
            ->addJoin(
                SpySalesOrderTableMap::COL_COMPANY_UUID,
                SpyCompanyTableMap::COL_UUID,
                Criteria::LEFT_JOIN,
            )
            ->withColumn(SpyCompanyTableMap::COL_NAME, static::FIELD_COMPANY_NAME)
            ->select([
                SpySalesOrderTableMap::COL_ORDER_REFERENCE,
                SpySalesOrderItemTableMap::COL_NAME,
                SpySalesOrderItemTableMap::COL_CREATED_AT,
                SpySalesOrderItemTableMap::COL_ID_SALES_ORDER_ITEM,
                SpySalesOrderItemTableMap::COL_FK_SALES_ORDER,
                SpySalesOrderTableMap::COL_FIRST_NAME,
                SpySalesOrderTableMap::COL_LAST_NAME,
                SpyOmsOrderItemStateTableMap::COL_NAME,
                SpySalesShipmentTableMap::COL_REQUESTED_DELIVERY_DATE,
            ]);

        return $query;
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
        $firstName = $item[SpySalesOrderTableMap::COL_FIRST_NAME] ?? '';
        $lastName = $item[SpySalesOrderTableMap::COL_LAST_NAME] ?? '';
        $customerName = trim($firstName . ' ' . $lastName);

        $rowData = [
            SpySalesOrderTableMap::COL_ORDER_REFERENCE => $item[SpySalesOrderTableMap::COL_ORDER_REFERENCE],
            static::FIELD_CUSTOMER => $customerName ?: 'N/A',
            static::FIELD_COMPANY_NAME => $item[static::FIELD_COMPANY_NAME] ?? 'N/A',
            SpySalesOrderItemTableMap::COL_NAME => $item[SpySalesOrderItemTableMap::COL_NAME] ?? '',
            static::FIELD_STATUS => $this->formatStatus($item[SpyOmsOrderItemStateTableMap::COL_NAME] ?? ''),
            static::FIELD_REQUESTED_DELIVERY_DATE => $this->formatRequestedDeliveryDate($item[SpySalesShipmentTableMap::COL_REQUESTED_DELIVERY_DATE] ?? null),
            SpySalesOrderItemTableMap::COL_CREATED_AT => $this->utilDateTimeService->formatDateTime($item[SpySalesOrderItemTableMap::COL_CREATED_AT]),
            static::COL_ACTIONS => $this->buildLinks($item),
        ];

        return $rowData;
    }

    /**
     * @param array<string, mixed> $service
     *
     * @return string
     */
    protected function buildLinks(array $service): string
    {
        $buttons = [];

        $buttons[] = $this->generateViewButton(
            Url::generate(static::URL_SALES_ORDER_DETAIL, [static::PARAM_ID_SALES_ORDER => $service[SpySalesOrderItemTableMap::COL_FK_SALES_ORDER]]),
            static::BUTTON_VIEW,
        );

        return implode(' ', $buttons);
    }

    protected function formatStatus(string $status): string
    {
        if (!$status) {
            return '<span class="label label-default">N/A</span>';
        }

        return sprintf('<span class="label label-default">%s</span>', htmlspecialchars($status));
    }

    protected function formatRequestedDeliveryDate(?string $requestedDeliveryDate): string
    {
        if (!$requestedDeliveryDate) {
            return 'N/A';
        }

        return $this->utilDateTimeService->formatDateTime($requestedDeliveryDate);
    }
}
