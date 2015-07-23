<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Table;

use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery;
use SprykerFeature\Shared\Library\Currency\CurrencyManager;

class OrdersTable extends AbstractTable
{
    const ID_SALES_ORDER = 'IdSalesOrder';
    const CREATED_AT = 'CreatedAt';
    const FK_CUSTOMER = 'FkCustomer';
    const EMAIL = 'Email';
    const GRAND_TOTAL = 'GrandTotal';
    const FIRST_NAME = 'FirstName';
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
        $config->setHeader([
            self::ID_SALES_ORDER => 'Order ID',
            self::CREATED_AT => 'Timestamp',
            self::FK_CUSTOMER => 'Customer Id',
            self::EMAIL => 'Email',
            self::FIRST_NAME => 'Billing Name',
            self::GRAND_TOTAL => 'Value',
            self::URL => 'Url',
        ]);
        $config->setSortable([
            self::CREATED_AT,
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
                self::ID_SALES_ORDER => $item[self::ID_SALES_ORDER],
                self::CREATED_AT => $item[self::CREATED_AT],
                self::FK_CUSTOMER => $item[self::FK_CUSTOMER],
                self::EMAIL => $item[self::EMAIL],
                self::FIRST_NAME => $item[self::FIRST_NAME],
                self::GRAND_TOTAL => $this->formatPrice($item[self::GRAND_TOTAL]),
                self::URL => sprintf(
                    '<a class="btn btn-primary" href="/sales/details/?id-sales-order=%d">View</a>',
                    $item['IdSalesOrder']
                ),
            ];
        }
        unset($queryResults);

        return $results;
    }

}
