<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Table;

use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Spryker\Shared\Library\DateFormatterInterface;
use Spryker\Shared\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Sales\Dependency\Facade\SalesToMoneyInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSalesAggregatorInterface;
use Spryker\Zed\Sales\Dependency\Service\SalesToUtilSanitizeInterface;

class OrdersTable extends AbstractTable
{

    const URL = 'URL';
    const ID_ORDER_ITEM_PROCESS = 'id-order-item-process';
    const ID_ORDER_ITEM_STATE = 'id-order-item-state';
    const FILTER = 'filter';
    const URL_SALES_DETAIL = '/sales/detail';
    const PARAM_ID_SALES_ORDER = 'id-sales-order';
    const GRAND_TOTAL = 'GrandTotal';
    const ITEM_STATE_NAMES_CSV = 'item_state_names_csv';
    const NUMBER_OF_ORDER_ITEMS = 'number_of_order_items';

    /**
     * @var \Spryker\Zed\Sales\Communication\Table\OrdersTableQueryBuilderInterface
     */
    protected $queryBuilder;

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
     * @var \Spryker\Zed\Sales\Dependency\Service\SalesToUtilSanitizeInterface
     */
    protected $sanitizeService;

    /**
     * @param \Spryker\Zed\Sales\Communication\Table\OrdersTableQueryBuilderInterface $queryBuilder
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToSalesAggregatorInterface $salesAggregatorFacade
     * @param \Spryker\Shared\Library\DateFormatterInterface $dateFormatter
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToMoneyInterface $moneyFacade
     * @param \Spryker\Zed\Sales\Dependency\Service\SalesToUtilSanitizeInterface $sanitizeService
     */
    public function __construct(
        OrdersTableQueryBuilderInterface $queryBuilder,
        SalesToSalesAggregatorInterface $salesAggregatorFacade,
        DateFormatterInterface $dateFormatter,
        SalesToMoneyInterface $moneyFacade,
        SalesToUtilSanitizeInterface $sanitizeService
    ) {
        $this->queryBuilder = $queryBuilder;
        $this->salesAggregatorFacade = $salesAggregatorFacade;
        $this->dateFormatter = $dateFormatter;
        $this->moneyFacade = $moneyFacade;
        $this->sanitizeService = $sanitizeService;
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
        $config->addRawColumn(SpySalesOrderTableMap::COL_EMAIL);

        $config->setDefaultSortColumnIndex(0);
        $config->setDefaultSortDirection(TableConfiguration::SORT_DESC);

        $this->persistFilters($config);

        return $config;
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
                SpySalesOrderTableMap::COL_EMAIL => $this->formatEmailAddress($item[SpySalesOrderTableMap::COL_EMAIL]),
                static::ITEM_STATE_NAMES_CSV => $this->groupItemStateNames($item[OrdersTableQueryBuilder::FIELD_ITEM_STATE_NAMES_CSV]),
                self::GRAND_TOTAL => $this->formatPrice($this->getGrandTotalByIdSalesOrder($item[SpySalesOrderTableMap::COL_ID_SALES_ORDER])),
                static::NUMBER_OF_ORDER_ITEMS => $item[OrdersTableQueryBuilder::FIELD_NUMBER_OF_ORDER_ITEMS],
                self::URL => implode(' ', $this->createActionUrls($item)),
            ];
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function formatCustomer(array $item)
    {
        $salutation = $item[SpySalesOrderTableMap::COL_SALUTATION];

        $customer = sprintf(
            '%s%s %s',
            $salutation ? $salutation . ' ' : '',
            $item[SpySalesOrderTableMap::COL_FIRST_NAME],
            $item[SpySalesOrderTableMap::COL_LAST_NAME]
        );

        $customer = $this->sanitizeService->escapeHtml($customer);

        if ($item[SpySalesOrderTableMap::COL_FK_CUSTOMER]) {
            $url = Url::generate('/customer/view', [
                'id-customer' => $item[SpySalesOrderTableMap::COL_FK_CUSTOMER],
            ]);
            $customer = '<a href="' . $url . '">' . $customer . '</a>';
        }

        return $customer;
    }

    /**
     * @param string $emailAddress
     *
     * @return string
     */
    protected function formatEmailAddress($emailAddress)
    {
        $escapedEmailAddress = $this->sanitizeService->escapeHtml($emailAddress);
        $emailAddressLink = sprintf('<a href="mailto:%1$s">%1$s</a>', $escapedEmailAddress);

        return $emailAddressLink;
    }

    /**
     * @param string $itemStateNamesCsv
     *
     * @return string
     */
    protected function groupItemStateNames($itemStateNamesCsv)
    {
        $itemStateNames = explode(',', $itemStateNamesCsv);
        $itemStateNames = array_map('trim', $itemStateNames);
        $distinctItemStateNames = array_unique($itemStateNames);
        $distinctItemStateNamesCsv = implode(', ', $distinctItemStateNames);

        return $distinctItemStateNamesCsv;
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
        $idOrderItemProcess = $this->request->query->getInt(self::ID_ORDER_ITEM_PROCESS);
        $idOrderItemItemState = $this->request->query->getInt(self::ID_ORDER_ITEM_STATE);
        $filter = $this->request->query->get(self::FILTER);

        return $this->queryBuilder->buildQuery($idOrderItemProcess, $idOrderItemItemState, $filter);
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
            SpySalesOrderTableMap::COL_ID_SALES_ORDER => '#',
            SpySalesOrderTableMap::COL_ORDER_REFERENCE => 'Order Reference',
            SpySalesOrderTableMap::COL_CREATED_AT => 'Created',
            SpySalesOrderTableMap::COL_FK_CUSTOMER => 'Customer Full Name',
            SpySalesOrderTableMap::COL_EMAIL => 'Email',
            static::ITEM_STATE_NAMES_CSV => 'Order State',
            self::GRAND_TOTAL => 'GrandTotal',
            static::NUMBER_OF_ORDER_ITEMS => 'Number of Items',
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
            static::NUMBER_OF_ORDER_ITEMS,
        ];
    }

}
