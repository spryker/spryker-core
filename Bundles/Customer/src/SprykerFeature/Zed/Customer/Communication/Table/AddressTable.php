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
    const ACTIONS = 'Actions';
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
            'IdCustomerAddress' => '#',
            'LastName'          => 'Last Name',
            'FirstName'         => 'First Name',
            'Address1'          => 'Address ',
            'Address2'          => 'Address (2nd line)',
            'Address3'          => 'Address (3rd line)',
            'Company'           => 'Company',
            'ZipCode'           => 'Zip Code',
            'City'              => 'City',
            'Country'           => 'Country',
            self::ACTIONS => self::ACTIONS,
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

        $lines = $this->runQuery($query, $config);

        if (empty($lines))
        {
            foreach ($lines as $key => $value)
            {
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

        $idCustomerAddress = !empty($details['IdCustomerAddress']) ? $details['IdCustomerAddress'] : false;
        if ($idCustomerAddress)
        {
            $links = [
                'Edit' => '/customer/address/edit/?id_customer=%d',
            ];

            $result = [];
            foreach ($links as $key => $value)
            {
                $result[] = sprintf('<a href="%s" class="btn btn-xs btn-primary">%s</a>', sprintf($value, $idCustomerAddress), $key);
            }

            $result = implode('&nbsp;&nbsp;&nbsp;', $result);
        }

        return $result;
    }
}
