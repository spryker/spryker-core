<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Table;

use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\Sales\Persistence\Propel\Map\SpySalesOrderTableMap;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery;
use SprykerFeature\Shared\Library\Currency\CurrencyManager;

class OrdersTable extends AbstractTable
{
    const URL = 'Url';

    /**
     * @param SpySalesOrderQuery $salesQuery
     */
    public function __construct(SpySalesOrderQuery $salesQuery)
    {
        $this->salesQuery = $salesQuery;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeaders([
            SpySalesOrderTableMap::COL_ID_SALES_ORDER => 'Order Id',
            SpySalesOrderTableMap::COL_CREATED_AT => 'Timestamp',
            SpySalesOrderTableMap::COL_FK_CUSTOMER => 'Customer Id',
            SpySalesOrderTableMap::COL_EMAIL => 'Email',
            SpySalesOrderTableMap::COL_FIRST_NAME => 'Billing Name',
            SpySalesOrderTableMap::COL_GRAND_TOTAL => 'Value',
            self::URL => 'Url',
        ]);
        $config->setSortable([
            SpySalesOrderTableMap::COL_CREATED_AT,
        ]);

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
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->salesQuery;
        $queryResults = $this->runQuery($query, $config);
        $results = [];
        foreach ($queryResults as $item) {
            $results[] = [
                SpySalesOrderTableMap::COL_ID_SALES_ORDER => $item[SpySalesOrderTableMap::COL_ID_SALES_ORDER],
                SpySalesOrderTableMap::COL_CREATED_AT => $item[SpySalesOrderTableMap::COL_CREATED_AT],
                SpySalesOrderTableMap::COL_FK_CUSTOMER => $item[SpySalesOrderTableMap::COL_FK_CUSTOMER],
                SpySalesOrderTableMap::COL_EMAIL => $item[SpySalesOrderTableMap::COL_EMAIL],
                SpySalesOrderTableMap::COL_FIRST_NAME => $item[SpySalesOrderTableMap::COL_FIRST_NAME],
                SpySalesOrderTableMap::COL_GRAND_TOTAL => $this->formatPrice($item[SpySalesOrderTableMap::COL_GRAND_TOTAL]),
                self::URL => sprintf(
                    '<a class="btn btn-primary" href="/sales/details/?id-sales-order=%d">View</a>',
                    $item[SpySalesOrderTableMap::COL_ID_SALES_ORDER]
                ),
            ];
        }
        unset($queryResults);

        return $results;
    }

}
