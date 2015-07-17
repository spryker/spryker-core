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
    const COUNTRY = 'Country';
    const CREATED_AT = 'CreatedAt';

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
            self::CREATED_AT => 'Registration Date',
            'Email'       => 'Email',
            'LastName'    => 'Last Name',
            'FirstName'   => 'First Name',
            'ZipCode'     => 'ZIP Code',
            'City'        => 'City',
            self::COUNTRY => self::COUNTRY,
            self::ACTIONS => self::ACTIONS,
        ]);
        $config->setSortable([
            'IdCustomer',
            self::CREATED_AT,
            'FirstName',
            'LastName',
            'ZipCode',
            self::COUNTRY,
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
            ->withColumn('billing.fk_country', self::COUNTRY);

        $lines = $this->runQuery($query, $config);

        if (!empty($lines))
        {
            foreach ($lines as $key => $value)
            {
                 $country = $this->customerQuery
                    ->useAddressQuery()
                    ->useCountryQuery()
                    ->findOneByIdCountry($lines[$key][self::COUNTRY]);

                $lines[$key][self::COUNTRY] = $country ? $country->getName() : '';
                $lines[$key][self::CREATED_AT] = gmdate(self::FORMAT, strtotime($value[self::CREATED_AT]));
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
