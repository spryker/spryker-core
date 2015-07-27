<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Table;

use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\Shipment\Persistence\Propel\Map\SpyShipmentMethodTableMap;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentMethod;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentMethodQuery;

class MethodTable extends AbstractTable
{
    const CARRIER = 'carrier';

    /**
     * @var SpyShipmentMethodQuery
     */
    protected $methodQuery;

    /**
     * @param SpyShipmentMethodQuery $methodQuery
     */
    public function __construct(SpyShipmentMethodQuery $methodQuery)
    {
        $this->methodQuery = $methodQuery;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyShipmentMethodTableMap::COL_ID_SHIPMENT_METHOD => '#',
            SpyShipmentMethodTableMap::COL_FK_SHIPMENT_CARRIER => 'Carrier',
            SpyShipmentMethodTableMap::COL_FK_GLOSSARY_KEY_METHOD_NAME => 'Method',
            SpyShipmentMethodTableMap::COL_IS_ACTIVE => 'Active'
        ]);
        $config->setUrl('table');

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->methodQuery;
        $queryResults = $this->runQuery($query, $config);
        $results = [];
        foreach ($queryResults as $item) {
            $results[] = [
                SpyShipmentMethodTableMap::COL_ID_SHIPMENT_METHOD
                    => $item[SpyShipmentMethodTableMap::COL_ID_SHIPMENT_METHOD],
                SpyShipmentMethodTableMap::COL_FK_SHIPMENT_CARRIER
                    => $item[SpyShipmentMethodTableMap::COL_FK_SHIPMENT_CARRIER],
                SpyShipmentMethodTableMap::COL_FK_GLOSSARY_KEY_METHOD_NAME
                    => $item[SpyShipmentMethodTableMap::COL_FK_GLOSSARY_KEY_METHOD_NAME],
                SpyShipmentMethodTableMap::COL_IS_ACTIVE
                    => $item[SpyShipmentMethodTableMap::COL_IS_ACTIVE]
                ];
        }
        unset($queryResults);

        return $results;
    }
}
