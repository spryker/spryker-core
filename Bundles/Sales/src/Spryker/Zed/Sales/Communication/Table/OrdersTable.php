<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Table;

use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Application\Business\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Shared\Library\Currency\CurrencyManager;
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
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $orderQuery
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $orderItemQuery
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToSalesAggregatorInterface $salesAggregatorFacade
     */
    public function __construct(
        SpySalesOrderQuery $orderQuery,
        SpySalesOrderItemQuery $orderItemQuery,
        SalesToSalesAggregatorInterface $salesAggregatorFacade
    ) {
        $this->orderQuery = $orderQuery;
        $this->orderItemQuery = $orderItemQuery;
        $this->salesAggregatorFacade = $salesAggregatorFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpySalesOrderTableMap::COL_ID_SALES_ORDER => 'Order Id',
            SpySalesOrderTableMap::COL_CREATED_AT => 'Timestamp',
            SpySalesOrderTableMap::COL_FK_CUSTOMER => 'Customer Id',
            SpySalesOrderTableMap::COL_EMAIL => 'Email',
            SpySalesOrderTableMap::COL_FIRST_NAME => 'Billing Name',
            self::GRAND_TOTAL => 'GrandTotal',
            self::URL => 'Url',
        ]);
        $config->setSortable([
            SpySalesOrderTableMap::COL_CREATED_AT,
        ]);

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
        $currencyManager = CurrencyManager::getInstance();
        $value = $currencyManager->convertCentToDecimal($value);

        return $currencyManager->format($value, $includeSymbol);
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
                SpySalesOrderTableMap::COL_CREATED_AT => $item[SpySalesOrderTableMap::COL_CREATED_AT],
                SpySalesOrderTableMap::COL_FK_CUSTOMER => $item[SpySalesOrderTableMap::COL_FK_CUSTOMER],
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

        $idOrderItemProcess = $this->request->get(self::ID_ORDER_ITEM_PROCESS);
        if (!$idOrderItemProcess) {
            return $query;
        }

        $idOrderItemItemState = $this->request->get(self::ID_ORDER_ITEM_STATE);
        $filter = $this->request->get(self::FILTER);
        $filterValue = null;
        if ($filter === 'day') {
            $filterValue = new \DateTime('-1 day');
        } elseif ($filter === 'week') {
            $filterValue = new \DateTime('-7 day');
        }

        $filterQuery = $this->orderItemQuery
            ->filterByFkOmsOrderProcess($idOrderItemProcess)
            ->filterByFkOmsOrderItemState($idOrderItemItemState);

        if ($filterValue) {
            $filterQuery->filterByLastStateChange($filterValue, Criteria::GREATER_EQUAL);
        } else {
            $filterQuery->filterByLastStateChange(new \DateTime('-7 day'), Criteria::LESS_THAN);
        }

        $orders = $filterQuery->groupByFkSalesOrder()
            ->select(SpySalesOrderItemTableMap::COL_FK_SALES_ORDER)
            ->find()
            ->toArray();

        $query->filterByIdSalesOrder($orders);

        return $query;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function persistFilters(TableConfiguration $config)
    {
        $idOrderItemProcess = $this->request->get(self::ID_ORDER_ITEM_PROCESS);
        if ($idOrderItemProcess) {
            $idOrderItemState = $this->request->get(self::ID_ORDER_ITEM_STATE);
            $filter = $this->request->get(self::FILTER);

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

}
