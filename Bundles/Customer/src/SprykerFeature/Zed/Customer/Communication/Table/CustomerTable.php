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
    const FORMAT = 'D, M jS, Y';
    const ACTIONS = 'Actions';

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
            'IdCustomer'  => '#',
            'Email'       => 'Email',
            'LastName'    => 'Last Name',
            'FirstName'   => 'First Name',
            'CreatedAt'   => 'Registration Date',
            'ZipCode'     => 'ZIP Code',
            'City'        => 'City',
            'Country'     => 'Country',
            self::ACTIONS => self::ACTIONS,
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

        $lines = $this->runQuery($query, $config);

        if (!empty($lines))
        {
            foreach ($lines as $key => $value)
            {
                $lines[$key]['CreatedAt'] = gmdate(self::FORMAT, strtotime($value['CreatedAt']));
                $lines[$key][self::ACTIONS] = $this->buildLinks($value);
            }
        }

        return $lines;
    }

    /**
     * @param $details
     *
     * @return array|string
     */
    private function buildLinks($details)
    {
        $result = '';

        $idCustomer = !empty($details['IdCustomer']) ? $details['IdCustomer'] : false;
        if ($idCustomer)
        {
            $links = [
                'Edit'             => '/customer/edit/?id_customer=%d',
                'Manage addresses' => '/customer/address/?id_customer=%d',
            ];

            $result = [];
            foreach ($links as $key => $value)
            {
                $result[] = sprintf('<a href="%s" class="btn btn-xs btn-primary">%s</a>', sprintf($value, $idCustomer), $key);
            }

            $result = implode('&nbsp;&nbsp;&nbsp;', $result);
        }

        return $result;
    }
}
