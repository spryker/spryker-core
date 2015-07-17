<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Table;

use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerAddressQuery;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class AddressTable extends AbstractTable
{

    const ACTIONS = 'Actions';
    const DEFAULT_BILLING_ADDRESS = 'default_billing_address';
    const DEFAULT_SHIPPING_ADDRESS = 'default_shipping_address';
    const ID_CUSTOMER_ADDRESS = 'IdCustomerAddress';
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
        $config->setHeaders([
            self::ID_CUSTOMER_ADDRESS => '#',
            'LastName'                => 'Last Name',
            'FirstName'               => 'First Name',
            'Address1'                => 'Address ',
            'Address2'                => 'Address (2nd line)',
            'Address3'                => 'Address (3rd line)',
            'Company'                 => 'Company',
            'ZipCode'                 => 'Zip Code',
            'City'                    => 'City',
            'Country'                 => 'Country',
            self::ACTIONS             => self::ACTIONS,
        ]);

        $config->setSortable([
            self::ID_CUSTOMER_ADDRESS,
            'FirstName',
            'LastName',
            'ZipCode',
            'Country',
        ]);

        $config->setSearchable([
            self::ID_CUSTOMER_ADDRESS,
            'LastName',
            'FirstName',
            'Address1',
            'Address2',
            'Address3',
            'ZipCode',
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
        $query = $this->addressQuery
            ->filterByFkCustomer($this->idCustomer)
            ->leftJoinCountry('country')
            ->withColumn('country.name', 'Country');
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
                $id = !empty($value[self::ID_CUSTOMER_ADDRESS]) ? $value[self::ID_CUSTOMER_ADDRESS] : false;

                $tags = [];
                if ((false === is_bool($id)) && ($id === $defaultBillingAddress)) {
                    $tags[] = '<span class="label label-danger" title="Default billing address">BILLING</span>';
                }
                if ((false === is_bool($id)) && ($id === $defaultShippingAddress)) {
                    $tags[] = '<span class="label label-danger" title="Default shipping address">SHIPPING</span>';
                }

                $lines[$key]['Address1'] = (!empty($tags) ? implode('&nbsp;', $tags) . '&nbsp;' : '') . $lines[$key]['Address1'];

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

        $idCustomerAddress = !empty($details[self::ID_CUSTOMER_ADDRESS]) ? $details[self::ID_CUSTOMER_ADDRESS] : false;
        if ($idCustomerAddress) {
            $links = [
                'Edit' => '/customer/address/edit/?id_customer_address=%d',
                'View' => '/customer/address/view/?id_customer_address=%d',
            ];

            $result = [];
            foreach ($links as $key => $value) {
                $result[] = sprintf('<a href="%s" class="btn btn-xs btn-primary">%s</a>', sprintf($value, $idCustomerAddress), $key);
            }

            $result = implode('&nbsp;&nbsp;&nbsp;', $result);
        }

        return $result;
    }

}
