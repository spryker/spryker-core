<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Table;

use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerAddressTableMap;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class CustomerTable extends AbstractTable
{

    const FORMAT = 'Y-m-d G:i:s';
    const ACTIONS = 'Actions';

    const COL_ZIP_CODE = 'zip_code';
    const COL_CITY = 'city';
    const COL_FK_COUNTRY = 'country';

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
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyCustomerTableMap::COL_ID_CUSTOMER => '#',
            SpyCustomerTableMap::COL_CREATED_AT => 'Registration Date',
            SpyCustomerTableMap::COL_EMAIL => 'Email',
            SpyCustomerTableMap::COL_LAST_NAME => 'Last Name',
            SpyCustomerTableMap::COL_FIRST_NAME => 'First Name',
            SpyCustomerTableMap::COL_PHONE => 'Phone Number',
            self::COL_ZIP_CODE => 'Zip Code',
            self::COL_CITY => 'City',
            self::COL_FK_COUNTRY => 'Country',
            self::ACTIONS => self::ACTIONS,
        ]);

        $config->setSortable([
            SpyCustomerTableMap::COL_ID_CUSTOMER,
            SpyCustomerTableMap::COL_CREATED_AT,
            SpyCustomerTableMap::COL_EMAIL,
            SpyCustomerTableMap::COL_LAST_NAME,
            SpyCustomerTableMap::COL_FIRST_NAME,
            self::COL_ZIP_CODE,
            self::COL_CITY,
        ]);

        $config->setUrl('table');

        $config->setSearchable([
            SpyCustomerTableMap::COL_ID_CUSTOMER,
            SpyCustomerTableMap::COL_EMAIL,
            SpyCustomerTableMap::COL_FIRST_NAME,
            SpyCustomerTableMap::COL_LAST_NAME,
            SpyCustomerAddressTableMap::COL_ZIP_CODE,
            SpyCustomerAddressTableMap::COL_CITY,
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
        $query = $this->customerQuery->leftJoinBillingAddress()
            ->withColumn(SpyCustomerAddressTableMap::COL_ZIP_CODE, self::COL_ZIP_CODE)
            ->withColumn(SpyCustomerAddressTableMap::COL_CITY, self::COL_CITY)
            ->withColumn(SpyCustomerAddressTableMap::COL_FK_COUNTRY, self::COL_FK_COUNTRY)
        ;

        $customersCollectionArray = $this->runQuery($query, $config);
        if (null === $customersCollectionArray) {
            return null;
        }

        foreach ($customersCollectionArray as $key => $value) {

            $country = $this->customerQuery->useAddressQuery()
                ->useCountryQuery()
                ->findOneByIdCountry($customersCollectionArray[$key][self::COL_FK_COUNTRY])
            ;

            $customersCollectionArray[$key][self::COL_FK_COUNTRY] = $country ? $country->getName() : '';
            $customersCollectionArray[$key][SpyCustomerTableMap::COL_CREATED_AT] = gmdate(
                self::FORMAT,
                strtotime($value[SpyCustomerTableMap::COL_CREATED_AT])
            );
            $customersCollectionArray[$key][self::ACTIONS] = $this->buildLinks($value);
        }

        return $customersCollectionArray;
    }

    /**
     * @param array $details
     *
     * @return string
     */
    private function buildLinks(array $details)
    {
        if (false === array_key_exists(SpyCustomerTableMap::COL_ID_CUSTOMER, $details)
            || true === empty($details[SpyCustomerTableMap::COL_ID_CUSTOMER])
        ) {
            return '';
        }
        $idCustomer = $details[SpyCustomerTableMap::COL_ID_CUSTOMER];

        return $this->generateViewButton('/customer/view/?id_customer=' . $idCustomer, 'View')
            . ' ' . $this->generateEditButton('/customer/edit/?id_customer=' . $idCustomer, 'Edit')
            . ' ' . $this->generateViewButton('/customer/address/?id_customer=' . $idCustomer, 'Manage Addresses')
        ;
    }

}
