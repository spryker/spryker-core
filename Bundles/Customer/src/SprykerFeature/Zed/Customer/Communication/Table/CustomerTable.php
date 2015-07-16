<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Table;

use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class CustomerTable extends AbstractTable
{

    /**
     * @var SpyCustomerQuery
     */
    protected $customerQuery;

    /**
     * @param SpyCustomerQuery $customerQuery
     */
    public function __construct(SpyCustomerQuery $customerQuery)
    {
        $this->customerQuery = $customerQuery;
    }

    /**
     * @param TableConfiguration $config
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeaders([
            'IdCustomer' => '#',
            'Email'      => 'Email',
            'LastName'   => 'Last Name',
            'FirstName'  => 'First Name',
            'CreatedAt'  => 'Registration Date',
            'ZipCode'    => 'ZIP Code',
            'City'       => 'City',
            'Country'    => 'Country',
        ]);
        $config->setSortable([
            'IdCustomer',
            'CreatedAt',
            'FirstName',
            'LastName',
            'ZipCode',
            'Country',
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
        $query = $this->customerQuery
            ->leftJoinBillingAddress('billing')
            ->withColumn('billing.first_name', 'FirstName')
            ->withColumn('billing.last_name', 'LastName')
            ->withColumn('billing.zip_code', 'ZipCode')
            ->withColumn('billing.city', 'City')
            ->withColumn('billing.fk_country', 'Country');

        return $this->runQuery($query, $config);
    }
}
