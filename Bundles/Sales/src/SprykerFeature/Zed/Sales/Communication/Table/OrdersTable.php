<?php

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
    const URL = 'url';

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
//        $config->setHeaders([
//            'IdCustomer'  => 'Customer ID',
//            'Email'       => 'Email',
//            'FirstName'   => 'First Name',
//            'LastName'   => 'Last Name',
//            'CreatedAt'   => 'Registration Date',
//            'ZipCode'     => 'ZIP Code',
//            'City'        => 'City',
//            'Country'     => 'Country',
//        ]);
//        $config->setSortable([
//            'IdCustomer',
//            'CreatedAt',
//            'FirstName',
//            'LastName',
//            'ZipCode',
//            'Country',
//        ]);

        $config->setHeaders([
            self::ID_SALES_ORDER => 'Order ID',
            self::CREATED_AT => 'Timestamp',
            self::FK_CUSTOMER => 'Customer Id',
            self::EMAIL => 'Email',
            self::GRAND_TOTAL => 'Value',
            self::FIRST_NAME => 'Billing Name',
            self::URL => 'Url',
        ]);
        $config->setSortable([
            self::CREATED_AT,
        ]);

        return $config;
    }

    protected function formatPrice($value, $includeSymbol = true)
    {
        $currencyManager = CurrencyManager::getInstance();
        $value = $currencyManager->convertCentToDecimal($value);

        return $currencyManager->format($value, $includeSymbol);
    }

    /**
     * @param TableConfiguration $config
     *
     * @return ObjectCollection
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->salesQuery;
        $results = $this->runQuery($query, $config);
        foreach ($results as &$v) {
            $v[self::GRAND_TOTAL] = $this->formatPrice($v[self::GRAND_TOTAL]);
            $v[self::URL] = sprintf(
                '<a class="btn btn-primary" href="/sales/details/?id-sales-order=%d">View</a>',
                $v['IdSalesOrder']
            );
        }

        return $results;
    }

}
