<?php

namespace SprykerFeature\Zed\Sales\Communication\Table;

use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery;

class OrdersTable extends AbstractTable
{

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
            'IdSalesOrder' => 'ID',
            'Email' => 'Email',
            'FkCustomer' => 'Customer Id',
            'FirstName' => 'First Name',
            'LastName' => 'Last Name',
        ]);
        $config->setSortable([
//            'email',
        ]);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return ObjectCollection
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->salesQuery;
//            ->leftJoinBillingAddress('billing')
//            ->withColumn('billing.first_name', 'FirstName')
//            ->withColumn('billing.last_name', 'LastName')
//            ->withColumn('billing.zip_code', 'ZipCode')
//            ->withColumn('billing.city', 'City')
//            ->withColumn('billing.fk_country', 'Country');

        return $this->runQuery($query, $config);
    }

}
