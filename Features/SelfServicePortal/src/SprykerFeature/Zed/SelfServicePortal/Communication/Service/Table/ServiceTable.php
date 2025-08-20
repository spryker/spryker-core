<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Table;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemMetadataTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
use SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ViewServiceController;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class ServiceTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const HEADER_ORDER_REFERENCE = 'Order Reference';

    /**
     * @var string
     */
    protected const HEADER_CUSTOMER = 'Customer';

    /**
     * @var string
     */
    protected const HEADER_COMPANY = 'Company';

    /**
     * @var string
     */
    protected const HEADER_SERVICE = 'Service';

    /**
     * @var string
     */
    protected const HEADER_TIME_AND_DATE = 'Time and Date';

    /**
     * @var string
     */
    protected const HEADER_CREATED_AT = 'Created';

    /**
     * @var string
     */
    protected const COL_ORDER_REFERENCE = 'order_reference';

    /**
     * @var string
     */
    protected const COL_COMPANY = 'company';

    /**
     * @var string
     */
    protected const COL_SERVICE = 'service';

    /**
     * @var string
     */
    protected const COL_SCHEDULED_AT = 'scheduled_at';

    /**
     * @var string
     */
    protected const COL_CREATED_AT = 'created_at';

    /**
     * @var string
     */
    protected const COL_FIRST_NAME = 'first_name';

    /**
     * @var string
     */
    protected const COL_LAST_NAME = 'last_name';

    /**
     * @var string
     */
    protected const COL_ACTIONS = 'Actions';

    /**
     * @var string
     */
    protected const COL_ID_SALES_ORDER = 'id_sales_order';

    /**
     * @var string
     */
    protected const COL_ID_SALES_ORDER_ITEM = 'id_sales_order_item';

    /**
     * @uses \Spryker\Zed\Sales\Communication\Controller\DetailController::PARAM_ID_SALES_ORDER
     *
     * @var string
     */
    protected const REQUEST_PARAM_ID_SALES_ORDER = 'id-sales-order';

    /**
     * @uses \Spryker\Zed\Sales\Communication\Controller\DetailController::indexAction()
     *
     * @var string
     */
    protected const URL_PATH_SALES_ORDER_DETAIL_PAGE = '/sales/detail';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ViewServiceController::indexAction()
     *
     * @var string
     */
    protected const URL_PATH_SELF_SERVICE_PORTAL_VIEW = '/self-service-portal/view-service';

    public function __construct(
        protected SpySalesOrderItemQuery $salesOrderItemQuery,
        protected UtilDateTimeServiceInterface $utilDateTimeService,
        protected SelfServicePortalConfig $SelfServicePortalConfig
    ) {
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader($this->getHeaders());
        $config->setSortable($this->getSortableColumns());
        $config->setSearchable($this->getSearchableColumns());
        $config->setRawColumns($this->getRawColumns());
        $config->setDefaultSortField(static::COL_SCHEDULED_AT);

        return $config;
    }

    /**
     * @return array<string, string>
     */
    protected function getHeaders(): array
    {
        return [
            static::COL_ORDER_REFERENCE => static::HEADER_ORDER_REFERENCE,
            static::COL_FIRST_NAME => static::HEADER_CUSTOMER,
            static::COL_COMPANY => static::HEADER_COMPANY,
            static::COL_SERVICE => static::HEADER_SERVICE,
            static::COL_SCHEDULED_AT => static::HEADER_TIME_AND_DATE,
            static::COL_CREATED_AT => static::HEADER_CREATED_AT,
            static::COL_ACTIONS => static::COL_ACTIONS,
        ];
    }

    /**
     * @return array<int|string, string>
     */
    protected function getSortableColumns(): array
    {
        return [
            static::COL_ORDER_REFERENCE,
            static::COL_FIRST_NAME,
            static::COL_COMPANY,
            static::COL_SERVICE,
            static::COL_SCHEDULED_AT,
            static::COL_CREATED_AT,
        ];
    }

    /**
     * @return list<string>
     */
    protected function getSearchableColumns(): array
    {
        return [
            SpyCustomerTableMap::COL_FIRST_NAME,
            SpyCustomerTableMap::COL_LAST_NAME,
            SpyCompanyTableMap::COL_NAME,
            SpySalesOrderItemTableMap::COL_NAME,
        ];
    }

    /**
     * @return list<string>
     */
    protected function getRawColumns(): array
    {
        return [
            static::COL_ORDER_REFERENCE,
            static::COL_ACTIONS,
        ];
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<array<string, mixed>>
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $query = $this->prepareQuery();
        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $item) {
            $results[] = $this->prepareTableRow($item);
        }

        return $results;
    }

    protected function prepareQuery(): SpySalesOrderItemQuery
    {
        $query = $this->salesOrderItemQuery->useSpySalesOrderItemProductClassQuery()
            ->useSpySalesProductClassQuery()
                ->filterByName($this->SelfServicePortalConfig->getServiceProductClassName())
            ->endUse()
            ->endUse();

        // @phpstan-ignore-next-line
        return $this->joinOrderData($query);
    }

    protected function joinOrderData(SpySalesOrderItemQuery $query): SpySalesOrderItemQuery
    {
        // @phpstan-ignore-next-line
        return $query
            ->useMetadataQuery(null, Criteria::LEFT_JOIN)
            ->withColumn(SpySalesOrderItemMetadataTableMap::COL_SCHEDULED_AT, static::COL_SCHEDULED_AT)
            ->endUse()
            ->useOrderQuery()
            ->withColumn(SpySalesOrderTableMap::COL_ORDER_REFERENCE, static::COL_ORDER_REFERENCE)
            ->withColumn(SpySalesOrderTableMap::COL_ID_SALES_ORDER, static::COL_ID_SALES_ORDER)
            ->addJoin(
                SpySalesOrderTableMap::COL_CUSTOMER_REFERENCE,
                SpyCustomerTableMap::COL_CUSTOMER_REFERENCE,
                Criteria::LEFT_JOIN,
            )
            ->addJoin(
                SpySalesOrderTableMap::COL_COMPANY_UUID,
                SpyCompanyTableMap::COL_UUID,
                Criteria::LEFT_JOIN,
            )
            ->withColumn(SpyCustomerTableMap::COL_FIRST_NAME, static::COL_FIRST_NAME)
            ->withColumn(SpyCustomerTableMap::COL_LAST_NAME, static::COL_LAST_NAME)
            ->withColumn(SpyCompanyTableMap::COL_NAME, static::COL_COMPANY)
            ->endUse()
            ->withColumn(SpySalesOrderItemTableMap::COL_NAME, static::COL_SERVICE)
            ->withColumn(SpySalesOrderItemTableMap::COL_ID_SALES_ORDER_ITEM, static::COL_ID_SALES_ORDER_ITEM)
            ->withColumn(SpySalesOrderItemTableMap::COL_CREATED_AT, static::COL_CREATED_AT);
    }

    /**
     * @param array<string, mixed> $item
     *
     * @return array<string, mixed>
     */
    protected function prepareTableRow(array $item): array
    {
        return [
            static::COL_ORDER_REFERENCE => $this->createOrderReferenceLink(
                $item[static::COL_ORDER_REFERENCE],
                $item[static::COL_ID_SALES_ORDER],
                $item[static::COL_ID_SALES_ORDER_ITEM],
            ),
            static::COL_FIRST_NAME => $this->formatCustomerName(
                $item[static::COL_FIRST_NAME],
                $item[static::COL_LAST_NAME],
            ),
            static::COL_COMPANY => $item[static::COL_COMPANY],
            static::COL_SERVICE => $item[static::COL_SERVICE],
            static::COL_SCHEDULED_AT => $item[static::COL_SCHEDULED_AT] ? $this->utilDateTimeService->formatDateTime($item[static::COL_SCHEDULED_AT]) : null,
            static::COL_CREATED_AT => $this->utilDateTimeService->formatDateTime($item[static::COL_CREATED_AT]),
            static::COL_ACTIONS => $this->buildLinks($item),
        ];
    }

    protected function createOrderReferenceLink(string $orderReference, int $idSalesOrder, int $idSalesOrderItem): string
    {
        $url = Url::generate(
            static::URL_PATH_SALES_ORDER_DETAIL_PAGE,
            [static::REQUEST_PARAM_ID_SALES_ORDER => $idSalesOrder],
            [Url::FRAGMENT => sprintf('id-sales-order-item-%s', $idSalesOrderItem)],
        );

        return sprintf(
            '<a href="%s">%s</a>',
            $url,
            $orderReference,
        );
    }

    protected function formatCustomerName(string $firstName, string $lastName): string
    {
        return sprintf('%s %s', $firstName, $lastName);
    }

    /**
     * @param array<mixed> $item
     *
     * @return string
     */
    protected function buildLinks(array $item): string
    {
        $buttons = [];

        $buttons[] = $this->generateViewButton(
            Url::generate(static::URL_PATH_SELF_SERVICE_PORTAL_VIEW, [
                ViewServiceController::REQUEST_PARAM_ID_SALES_ORDER_ITEM => $item[static::COL_ID_SALES_ORDER_ITEM],
            ]),
            'View',
            [
                'data-qa' => 'view-button',
            ],
        );

        return implode(' ', $buttons);
    }
}
