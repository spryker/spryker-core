<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Table;

use SprykerFeature\Zed\Customer\Persistence\Propel\Base\SpyCustomerAddressQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class AddressTable extends AbstractTable
{
    /**
     * @var SpyCustomerAddressQuery
     */
    protected $addressQuery;

    protected $idCustomer;

    /**
     * @param SpyCustomerAddressQuery $addressQuery
     */
    public function __construct(SpyCustomerAddressQuery $addressQuery, $idCustomer)
    {
        $this->addressQuery = $addressQuery;
        $this->idCustomer = $idCustomer;
    }

    /**
     * @param TableConfiguration $config
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeaders([
            'IdCustomerAddress'  => '#',
            'LastName'   => 'Last Name',
            'FirstName'   => 'First Name',
            'Address1'   => 'Address ',
            'Address2'     => 'Address (2nd line)',
            'Address3' => 'Address (3rd line)',
            'Company' => 'Company',
            'ZipCode' => 'Zip Code',
            'City'        => 'City',
            'Country'     => 'Country',
        ]);
        $config->setSortable([
            'IdCustomerAddress',
            'FirstName',
            'LastName',
            'ZipCode',
            'Country',
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
        ->leftJoinCountry('country')
        ->withColumn('country.name', 'Country');

        return $this->runQuery($query, $config);
    }
}
