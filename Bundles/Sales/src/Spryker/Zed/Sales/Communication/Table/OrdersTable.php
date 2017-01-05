<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Table;

use DateTime;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Library\DateFormatterInterface;
use Spryker\Shared\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Library\Sanitize\Html;
use Spryker\Zed\Sales\Dependency\Facade\SalesToMoneyInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSalesAggregatorInterface;

class OrdersTable extends AbstractTable
{

    const URL = 'URL';
    const ID_ORDER_ITEM_PROCESS = 'id-order-item-process';
    const ID_ORDER_ITEM_STATE = 'id-order-item-state';
    const FILTER = 'filter';
    const URL_SALES_DETAIL = '/sales/detail';
    const PARAM_ID_SALES_ORDER = 'id-sales-order';
    const GRAND_TOTAL = 'GrandTotal';
    const FILTER_DAY = 'day';
    const FILTER_WEEK = 'week';

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected $orderQuery;

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected $orderItemQuery;

    /**
     * @var \Spryker\Zed\Sales\Business\SalesFacade
     */
    protected $salesAggregatorFacade;

    /**
     * @var \Spryker\Shared\Library\DateFormatterInterface
     */
    protected $dateFormatter;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $orderQuery
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $orderItemQuery
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToSalesAggregatorInterface $salesAggregatorFacade
     * @param \Spryker\Shared\Library\DateFormatterInterface $dateFormatter
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToMoneyInterface $moneyFacade
     */
    public function __construct(
        SpySalesOrderQuery $orderQuery,
        SpySalesOrderItemQuery $orderItemQuery,
        SalesToSalesAggregatorInterface $salesAggregatorFacade,
        DateFormatterInterface $dateFormatter,
        SalesToMoneyInterface $moneyFacade
    ) {
        $this->orderQuery = $orderQuery;
        $this->orderItemQuery = $orderItemQuery;
        $this->salesAggregatorFacade = $salesAggregatorFacade;
        $this->dateFormatter = $dateFormatter;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader($this->getHeaderFields());
        $config->setSearchable($this->getSearchableFields());
        $config->setSortable($this->getSortableFields());

        $config->addRawColumn(self::URL);
        $config->addRawColumn(SpySalesOrderTableMap::COL_FK_CUSTOMER);

        $config->setDefaultSortColumnIndex(0);
        $config->setDefaultSortDirection(TableConfiguration::SORT_DESC);

        $this->persistFilters($config);

        return $config;
    }

    /**
     * @param int $value
     * @param bool $includeSymbol
     *
     * @return string
     */
    protected function formatPrice($value, $includeSymbol = true)
    {
        $moneyTransfer = $this->moneyFacade->fromInteger($value);

        if ($includeSymbol) {
            return $this->moneyFacade->formatWithSymbol($moneyTransfer);
        }

        return $this->moneyFacade->formatWithoutSymbol($moneyTransfer);
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function formatCustomer(array $item)
    {
        $customer = $item[SpySalesOrderTableMap::COL_FIRST_NAME] . ' ' . $item[SpySalesOrderTableMap::COL_LAST_NAME];
        $customer = Html::escape($customer);
        if ($item[SpySalesOrderTableMap::COL_FK_CUSTOMER]) {
            $url = Url::generate('/customer/view', [
                'id-customer' => $item[SpySalesOrderTableMap::COL_FK_CUSTOMER],
            ]);
            $customer = '<a href="' . $url . '">' . $customer . '</a>';
        }

        return $customer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->buildQuery();
        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $item) {
            $results[] = [
                SpySalesOrderTableMap::COL_ID_SALES_ORDER => $item[SpySalesOrderTableMap::COL_ID_SALES_ORDER],
                SpySalesOrderTableMap::COL_ORDER_REFERENCE => $item[SpySalesOrderTableMap::COL_ORDER_REFERENCE],
                SpySalesOrderTableMap::COL_CREATED_AT => $this->dateFormatter->dateTime($item[SpySalesOrderTableMap::COL_CREATED_AT]),
                SpySalesOrderTableMap::COL_FK_CUSTOMER => $this->formatCustomer($item),
                SpySalesOrderTableMap::COL_EMAIL => $item[SpySalesOrderTableMap::COL_EMAIL],
                SpySalesOrderTableMap::COL_FIRST_NAME => $item[SpySalesOrderTableMap::COL_FIRST_NAME],
                self::GRAND_TOTAL => $this->formatPrice($this->getGrandTotalByIdSalesOrder($item[SpySalesOrderTableMap::COL_ID_SALES_ORDER])),
                self::URL => implode(' ', $this->createActionUrls($item)),
            ];
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function createActionUrls(array $item)
    {
        $urls = [];

        $urls[] = $this->generateViewButton(
            Url::generate(self::URL_SALES_DETAIL, [
                self::PARAM_ID_SALES_ORDER => $item[SpySalesOrderTableMap::COL_ID_SALES_ORDER],
            ]),
            'View'
        );

        return $urls;
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function buildQuery()
    {
        $query = $this->orderQuery;

        $idOrderItemProcess = $this->request->query->getInt(self::ID_ORDER_ITEM_PROCESS);
        if (!$idOrderItemProcess) {
            return $query;
        }

        $idOrderItemItemState = $this->request->query->getInt(self::ID_ORDER_ITEM_STATE);

        $filterQuery = $this->orderItemQuery
            ->filterByFkOmsOrderProcess($idOrderItemProcess)
            ->filterByFkOmsOrderItemState($idOrderItemItemState);

        $filter = $this->request->query->get(self::FILTER);
        $this->addRangeFilter($filterQuery, $filter);

        $orders = $filterQuery->groupByFkSalesOrder()
            ->select(SpySalesOrderItemTableMap::COL_FK_SALES_ORDER)
            ->find()
            ->toArray();

        $query->filterByIdSalesOrder($orders, Criteria::IN);

        return $query;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function persistFilters(TableConfiguration $config)
    {
        $idOrderItemProcess = $this->request->query->getInt(self::ID_ORDER_ITEM_PROCESS);
        if ($idOrderItemProcess) {
            $idOrderItemState = $this->request->query->getInt(self::ID_ORDER_ITEM_STATE);
            $filter = $this->request->query->get(self::FILTER);

            $config->setUrl(
                sprintf(
                    'table?id-order-item-process=%s&id-order-item-state=%s&filter=%s',
                    $idOrderItemProcess,
                    $idOrderItemState,
                    $filter
                )
            );
        }
    }

    /**
     * @param int $idSalesOrder
     *
     * @return int
     */
    protected function getGrandTotalByIdSalesOrder($idSalesOrder)
    {
        $orderTransfer = $this->salesAggregatorFacade->getOrderTotalsByIdSalesOrder($idSalesOrder);

        return $orderTransfer->getTotals()->getGrandTotal();
    }

    /**
     * @return array
     */
    protected function getHeaderFields()
    {
        return [
            SpySalesOrderTableMap::COL_ID_SALES_ORDER => 'Order Id',
            SpySalesOrderTableMap::COL_ORDER_REFERENCE => 'Order Reference',
            SpySalesOrderTableMap::COL_CREATED_AT => 'Created',
            SpySalesOrderTableMap::COL_FK_CUSTOMER => 'Customer',
            SpySalesOrderTableMap::COL_EMAIL => 'Email',
            SpySalesOrderTableMap::COL_FIRST_NAME => 'Billing Name',
            self::GRAND_TOTAL => 'GrandTotal',
            self::URL => 'Actions',
        ];
    }

    /**
     * @return array
     */
    protected function getSearchableFields()
    {
        return [
            SpySalesOrderTableMap::COL_ID_SALES_ORDER,
            SpySalesOrderTableMap::COL_ORDER_REFERENCE,
            SpySalesOrderTableMap::COL_CREATED_AT,
            SpySalesOrderTableMap::COL_EMAIL,
            SpySalesOrderTableMap::COL_FIRST_NAME,
        ];
    }

    /**
     * @return array
     */
    protected function getSortableFields()
    {
        return [
            SpySalesOrderTableMap::COL_ID_SALES_ORDER,
            SpySalesOrderTableMap::COL_ORDER_REFERENCE,
            SpySalesOrderTableMap::COL_CREATED_AT,
            SpySalesOrderTableMap::COL_EMAIL,
            SpySalesOrderTableMap::COL_FIRST_NAME,
        ];
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $filterQuery
     * @param string $filter
     *
     * @return void
     */
    protected function addRangeFilter(SpySalesOrderItemQuery $filterQuery, $filter)
    {
        if ($filter === self::FILTER_DAY) {
            $filterQuery->filterByLastStateChange(new DateTime('-1 day'), Criteria::GREATER_THAN);
        } elseif ($filter === self::FILTER_WEEK) {
            $filterQuery->filterByLastStateChange(new DateTime('-1 day'), Criteria::LESS_EQUAL);
            $filterQuery->filterByLastStateChange(new DateTime('-7 day'), Criteria::GREATER_EQUAL);
        } else {
            $filterQuery->filterByLastStateChange(new DateTime('-7 day'), Criteria::LESS_THAN);
        }
    }

}
