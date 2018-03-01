<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Table;

use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMoneyFacadeInterface;
use Spryker\Zed\Customer\Dependency\QueryContainer\CustomerToSalesQueryContainerInterface;
use Spryker\Zed\Customer\Dependency\Service\CustomerToUtilDateTimeServiceInterface;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class OrderTable extends AbstractTable
{
    const FIELD_GRAND_TOTAL = 'GRAND_TOTAL';
    const FIELD_ORDER_STATE = 'ORDER_STATE';
    const FIELD_ITEM_COUNT = 'ITEM_COUNT';
    const FIELD_ITEM_STATES = 'ITEM_STATES';
    const FIELD_ACTIONS = 'ACTIONS';
    const URL_SALES_DETAIL = '/sales/detail';
    const PARAM_ID_SALES_ORDER = 'id-sales-order';

    /**
     * @var int
     */
    protected $idCustomer;

    /**
     * @var \Spryker\Zed\Customer\Dependency\QueryContainer\CustomerToSalesQueryContainerInterface
     */
    protected $salesQueryContainer;

    /**
     * @var \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface
     */
    protected $customerQueryContainer;

    /**
     * @var \Spryker\Zed\Customer\Dependency\Service\CustomerToUtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @var \Spryker\Zed\Customer\Dependency\Facade\CustomerToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param int $idCustomer
     * @param \Spryker\Zed\Customer\Dependency\QueryContainer\CustomerToSalesQueryContainerInterface $salesQueryContainer
     * @param \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface $customerQueryContainer
     * @param \Spryker\Zed\Customer\Dependency\Service\CustomerToUtilDateTimeServiceInterface $utilDateTimeService
     * @param \Spryker\Zed\Customer\Dependency\Facade\CustomerToMoneyFacadeInterface $moneyFacade
     */
    public function __construct(
        $idCustomer,
        CustomerToSalesQueryContainerInterface $salesQueryContainer,
        CustomerQueryContainerInterface $customerQueryContainer,
        CustomerToUtilDateTimeServiceInterface $utilDateTimeService,
        CustomerToMoneyFacadeInterface $moneyFacade
    ) {
        $this->idCustomer = $idCustomer;
        $this->salesQueryContainer = $salesQueryContainer;
        $this->customerQueryContainer = $customerQueryContainer;
        $this->utilDateTimeService = $utilDateTimeService;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpySalesOrderTableMap::COL_ID_SALES_ORDER => '#',
            SpySalesOrderTableMap::COL_ORDER_REFERENCE => 'Order Reference',
            SpySalesOrderTableMap::COL_CREATED_AT => 'Created',
            static::FIELD_ORDER_STATE => 'Order State',
            static::FIELD_GRAND_TOTAL => 'GrandTotal',
            static::FIELD_ITEM_COUNT => 'Number of items',
            static::FIELD_ACTIONS => 'Actions',
        ]);

        $config->addRawColumn(static::FIELD_ACTIONS);

        $config->setSortable([
            SpySalesOrderTableMap::COL_ID_SALES_ORDER,
            SpySalesOrderTableMap::COL_ORDER_REFERENCE,
            SpySalesOrderTableMap::COL_CREATED_AT,
        ]);

        $config->setSearchable([
            SpySalesOrderTableMap::COL_ID_SALES_ORDER,
            SpySalesOrderTableMap::COL_ORDER_REFERENCE,
            SpySalesOrderTableMap::COL_CREATED_AT,
        ]);

        $config->setDefaultSortField(SpySalesOrderTableMap::COL_ID_SALES_ORDER, TableConfiguration::SORT_DESC);

        $config->setUrl(sprintf('order-table?id-customer=%d', $this->idCustomer));

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $customerEntity = $this->customerQueryContainer->queryCustomerById($this->idCustomer)->findOne();
        $query = $this->salesQueryContainer->querySalesOrder()
            ->addLastOrderGrandTotalToResult(static::FIELD_GRAND_TOTAL)
            ->addItemStateNameAggregationToResult(static::FIELD_ORDER_STATE)
            ->addItemCountToResult(static::FIELD_ITEM_COUNT)
            ->filterByCustomerReference($customerEntity->getCustomerReference());

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $item) {
            $results[] = [
                SpySalesOrderTableMap::COL_ID_SALES_ORDER => $item[SpySalesOrderTableMap::COL_ID_SALES_ORDER],
                SpySalesOrderTableMap::COL_ORDER_REFERENCE => $item[SpySalesOrderTableMap::COL_ORDER_REFERENCE],
                SpySalesOrderTableMap::COL_CREATED_AT => $this->utilDateTimeService->formatDateTime($item[SpySalesOrderTableMap::COL_CREATED_AT]),
                static::FIELD_ORDER_STATE => $this->groupItemStateNames($item[static::FIELD_ORDER_STATE]),
                static::FIELD_GRAND_TOTAL => $this->getGrandTotal($item),
                static::FIELD_ITEM_COUNT => $item[static::FIELD_ITEM_COUNT],
                static::FIELD_ACTIONS => $this->createActionUrls($item),
            ];
        }
        unset($queryResults);

        return $results;
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
     * @param array $item
     *
     * @return int
     */
    protected function getGrandTotal(array $item)
    {
        $currencyIsoCode = $item[SpySalesOrderTableMap::COL_CURRENCY_ISO_CODE];
        if (!isset($item[static::FIELD_GRAND_TOTAL])) {
            return $this->formatPrice(0, true, $currencyIsoCode);
        }

        return $this->formatPrice((int)$item[static::FIELD_GRAND_TOTAL], true, $currencyIsoCode);
    }

    /**
     * @param int $value
     * @param bool $includeSymbol
     * @param null|string $currencyIsoCode
     *
     * @return string
     */
    protected function formatPrice($value, $includeSymbol = true, $currencyIsoCode = null)
    {
        $moneyTransfer = $this->moneyFacade->fromInteger($value, $currencyIsoCode);

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
    protected function createActionUrls(array $item)
    {
        return $this->generateViewButton(
            Url::generate(static::URL_SALES_DETAIL, [
                static::PARAM_ID_SALES_ORDER => $item[SpySalesOrderTableMap::COL_ID_SALES_ORDER],
            ]),
            'View'
        );
    }
}
