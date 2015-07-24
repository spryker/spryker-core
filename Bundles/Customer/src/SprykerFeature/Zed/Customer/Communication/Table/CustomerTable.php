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
            $this->buildAlias(SpyCustomerAddressTableMap::COL_ZIP_CODE) => 'Zip Code',
            $this->buildAlias(SpyCustomerAddressTableMap::COL_CITY) => 'City',
            $this->buildAlias(SpyCustomerAddressTableMap::COL_FK_COUNTRY) => 'Country',
            self::ACTIONS => self::ACTIONS,
        ]);

        $config->setSortable([
            SpyCustomerTableMap::COL_ID_CUSTOMER,
            SpyCustomerTableMap::COL_CREATED_AT,
            SpyCustomerTableMap::COL_EMAIL,
            SpyCustomerTableMap::COL_LAST_NAME,
            SpyCustomerTableMap::COL_FIRST_NAME,
            $this->buildAlias(SpyCustomerAddressTableMap::COL_ZIP_CODE),
            $this->buildAlias(SpyCustomerAddressTableMap::COL_CITY),
        ]);

        $config->setUrl('table');

        $config->setSearchable([
            SpyCustomerTableMap::COL_ID_CUSTOMER,
            SpyCustomerTableMap::COL_EMAIL,
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
            ->withColumn(SpyCustomerAddressTableMap::COL_ZIP_CODE)
            ->withColumn(SpyCustomerAddressTableMap::COL_CITY)
            ->withColumn(SpyCustomerAddressTableMap::COL_FK_COUNTRY)
        ;

        $lines = $this->runQuery($query, $config);
        if (!empty($lines)) {

            foreach ($lines as $key => $value) {

                $country = $this->customerQuery->useAddressQuery()
                    ->useCountryQuery()
                    ->findOneByIdCountry($lines[$key][$this->buildAlias(SpyCustomerAddressTableMap::COL_FK_COUNTRY)])
                ;

                $lines[$key][$this->buildAlias(SpyCustomerAddressTableMap::COL_FK_COUNTRY)] = $country ? $country->getName() : '';
                $lines[$key][$this->buildAlias(SpyCustomerTableMap::COL_CREATED_AT)] = gmdate(
                    self::FORMAT,
                    strtotime($value[SpyCustomerTableMap::COL_CREATED_AT])
                );
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

        $idCustomer = !empty($details[SpyCustomerTableMap::COL_ID_CUSTOMER]) ? $details[SpyCustomerTableMap::COL_ID_CUSTOMER] : false;
        if (false !== $idCustomer) {
            $links = [
                'View' => '/customer/view/?id_customer=%d',
                'Edit' => '/customer/edit/?id_customer=%d',
                'Manage addresses' => '/customer/address/?id_customer=%d',
            ];

            $result = [];
            $template = '<a href="%s" class="btn btn-xs btn-white">%s</a>';
            foreach ($links as $key => $value) {
                $result[] = sprintf($template, sprintf($value, $idCustomer), $key);
            }

            $result = implode('&nbsp;&nbsp;&nbsp;', $result);
        }

        return $result;
    }

}
