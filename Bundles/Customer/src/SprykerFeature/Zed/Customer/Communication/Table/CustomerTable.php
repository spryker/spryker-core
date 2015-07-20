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
    const ID_CUSTOMER = 'IdCustomer';
    const EMAIL = 'Email';
    const LAST_NAME = 'LastName';
    const FIRST_NAME = 'FirstName';
    const ZIP_CODE = 'ZipCode';
    const CITY = 'City';

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
            self::ID_CUSTOMER => '#',
            self::CREATED_AT => 'Registration Date',
            self::EMAIL => self::EMAIL,
            self::LAST_NAME => 'Last Name',
            self::FIRST_NAME => 'First Name',
            self::ZIP_CODE => 'ZIP Code',
            self::CITY => self::CITY,
            self::COUNTRY => self::COUNTRY,
            self::ACTIONS => self::ACTIONS,
        ]);

        $config->setSortable([
            self::ID_CUSTOMER,
            self::CREATED_AT,
            self::FIRST_NAME,
            self::LAST_NAME,
            self::ZIP_CODE,
            self::COUNTRY,
        ]);

        $config->setUrl('table');

        $config->setSearchable([
            self::ID_CUSTOMER,
            self::EMAIL,
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
        $query = $this->customerQuery->leftJoinBillingAddress('billing')
            ->withColumn('billing.first_name', self::FIRST_NAME)
            ->withColumn('billing.last_name', self::LAST_NAME)
            ->withColumn('billing.zip_code', self::ZIP_CODE)
            ->withColumn('billing.city', self::CITY)
            ->withColumn('billing.fk_country', self::COUNTRY)
        ;

        $lines = $this->runQuery($query, $config);
        if (!empty($lines)) {
            foreach ($lines as $key => $value) {

                $country = $this->customerQuery->useAddressQuery()
                    ->useCountryQuery()
                    ->findOneByIdCountry($lines[$key][self::COUNTRY])
                ;

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

        $idCustomer = !empty($details[self::ID_CUSTOMER]) ? $details[self::ID_CUSTOMER] : false;
        if (false !== $idCustomer) {
            $links = [
                'Edit' => '/customer/edit/?id_customer=%d',
                'Manage addresses' => '/customer/address/?id_customer=%d',
            ];

            $result = [];
            foreach ($links as $key => $value) {
                $result[] = sprintf('<a href="%s" class="btn btn-xs btn-primary">%s</a>', sprintf($value, $idCustomer), $key);
            }

            $result = implode('&nbsp;&nbsp;&nbsp;', $result);
        }

        return $result;
    }

}
