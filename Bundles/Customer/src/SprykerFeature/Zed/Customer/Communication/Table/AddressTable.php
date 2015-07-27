<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Table;

use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerAddressTableMap;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerAddressQuery;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class AddressTable extends AbstractTable
{

    const ACTIONS = 'Actions';

    const DEFAULT_BILLING_ADDRESS = 'default_billing_address';
    const DEFAULT_SHIPPING_ADDRESS = 'default_shipping_address';

    /**
     * @var SpyCustomerAddressQuery
     */
    protected $addressQuery;

    /**
     * @var SpyCustomerQuery
     */
    protected $customerQuery;

    /**
     * @var
     */
    protected $idCustomer;

    /**
     * @param SpyCustomerAddressQuery $addressQuery
     */
    public function __construct(SpyCustomerAddressQuery $addressQuery, SpyCustomerQuery $customerQuery, $idCustomer)
    {
        $this->addressQuery = $addressQuery;
        $this->customerQuery = $customerQuery;
        $this->idCustomer = $idCustomer;
    }

    /**
     * @param TableConfiguration $config
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyCustomerAddressTableMap::COL_ID_CUSTOMER_ADDRESS => '#',
            SpyCustomerAddressTableMap::COL_LAST_NAME => 'Last Name',
            SpyCustomerAddressTableMap::COL_FIRST_NAME => 'First Name',
            SpyCustomerAddressTableMap::COL_ADDRESS1 => 'Address ',
            SpyCustomerAddressTableMap::COL_ADDRESS2 => 'Address (2nd line)',
            SpyCustomerAddressTableMap::COL_ADDRESS3 => 'Address (3rd line)',
            SpyCustomerAddressTableMap::COL_COMPANY => SpyCustomerAddressTableMap::COL_COMPANY,
            SpyCustomerAddressTableMap::COL_ZIP_CODE => 'Zip Code',
            SpyCustomerAddressTableMap::COL_CITY => 'City',
            $this->buildAlias(SpyCustomerAddressTableMap::COL_FK_COUNTRY) => 'Country',
            self::ACTIONS => 'Actions',
        ]);

        $config->setSortable([
            SpyCustomerAddressTableMap::COL_ID_CUSTOMER_ADDRESS,
            SpyCustomerAddressTableMap::COL_FIRST_NAME,
            SpyCustomerAddressTableMap::COL_LAST_NAME,
            SpyCustomerAddressTableMap::COL_ZIP_CODE,
            SpyCustomerAddressTableMap::COL_FK_COUNTRY,
        ]);

        $config->setSearchable([
            SpyCustomerAddressTableMap::COL_ID_CUSTOMER_ADDRESS,
            SpyCustomerAddressTableMap::COL_LAST_NAME,
            SpyCustomerAddressTableMap::COL_FIRST_NAME,
            SpyCustomerAddressTableMap::COL_ADDRESS1,
            SpyCustomerAddressTableMap::COL_ADDRESS2,
            SpyCustomerAddressTableMap::COL_ADDRESS3,
            SpyCustomerAddressTableMap::COL_ZIP_CODE,
        ]);

        $config->setUrl(sprintf('table?id_customer=%d', $this->idCustomer));

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return ObjectCollection
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->addressQuery->filterByFkCustomer($this->idCustomer)
            ->leftJoinCountry('country')
            ->withColumn('country.name', $this->buildAlias(SpyCustomerAddressTableMap::COL_FK_COUNTRY))
        ;
        $lines = $this->runQuery($query, $config);

        $customer = $this->customerQuery->findOneByIdCustomer($this->idCustomer);

        $defaultBillingAddress = $defaultShippingAddress = false;
        if (false === is_null($customer)) {
            $customer = $customer->toArray();

            $defaultBillingAddress = !empty($customer[self::DEFAULT_BILLING_ADDRESS]) ? $customer[self::DEFAULT_BILLING_ADDRESS] : false;
            $defaultShippingAddress = !empty($customer[self::DEFAULT_SHIPPING_ADDRESS]) ? $customer[self::DEFAULT_SHIPPING_ADDRESS] : false;
        }

        if (!empty($lines)) {
            foreach ($lines as $key => $value) {
                $id = !empty($value[SpyCustomerAddressTableMap::COL_ID_CUSTOMER_ADDRESS]) ? $value[SpyCustomerAddressTableMap::COL_ID_CUSTOMER_ADDRESS] : false;

                $tags = [];
                if ((false === is_bool($id)) && ($id === $defaultBillingAddress)) {
                    $tags[] = '<span class="label label-danger" title="Default billing address">BILLING</span>';
                }
                if ((false === is_bool($id)) && ($id === $defaultShippingAddress)) {
                    $tags[] = '<span class="label label-danger" title="Default shipping address">SHIPPING</span>';
                }

                $lines[$key][SpyCustomerAddressTableMap::COL_ADDRESS1] = (!empty($tags) ? implode('&nbsp;', $tags) . '&nbsp;' : '') . $lines[$key][SpyCustomerAddressTableMap::COL_ADDRESS1];

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

        $idCustomerAddress = !empty($details[SpyCustomerAddressTableMap::COL_ID_CUSTOMER_ADDRESS]) ? $details[SpyCustomerAddressTableMap::COL_ID_CUSTOMER_ADDRESS] : false;
        if (false !== $idCustomerAddress) {
            $links = [
                'Edit' => '/customer/address/edit/?id_customer_address=',
                'View' => '/customer/address/view/?id_customer_address=',
            ];

            $result = [];
            foreach ($links as $key => $value) {
                $result[] = '<a href="' . $value . $idCustomerAddress .'" class="btn btn-xs btn-white">' . $key . '</a>';
            }

            $result = implode('&nbsp;&nbsp;&nbsp;', $result);
        }

        return $result;
    }

}
