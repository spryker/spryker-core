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
    const CARRIER = 'Carrier';
    const METHOD = 'Method';
    const DESCRIPTION = 'Description';
    const PRICE = 'Price';
    const ACTIVE = 'Active';
    const ACTIONS = 'Actions';

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
            SpyShipmentMethodTableMap::COL_FK_SHIPMENT_CARRIER => self::CARRIER,
            SpyShipmentMethodTableMap::COL_FK_GLOSSARY_KEY_METHOD_NAME
                => self::METHOD,
            SpyShipmentMethodTableMap::COL_FK_GLOSSARY_KEY_METHOD_DESCRIPTION
                => self::DESCRIPTION,
            SpyShipmentMethodTableMap::COL_PRICE => self::PRICE,
            SpyShipmentMethodTableMap::COL_IS_ACTIVE => self::ACTIVE,
            self::ACTIONS => self::ACTIONS
        ]);

        $config->setSortable([
            SpyShipmentMethodTableMap::COL_PRICE
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
            $methodQuery = clone $query;
            $method = $methodQuery
                ->findOneByIdShipmentMethod(
                    $item[SpyShipmentMethodTableMap::COL_ID_SHIPMENT_METHOD]
                );

            $results[] = [
                SpyShipmentMethodTableMap::COL_ID_SHIPMENT_METHOD
                    => $item[SpyShipmentMethodTableMap::COL_ID_SHIPMENT_METHOD],
                SpyShipmentMethodTableMap::COL_FK_SHIPMENT_CARRIER
                    => $method
                    ->getShipmentCarrier()
                    ->getSpyGlossaryKey()
                    ->getKey(),
                SpyShipmentMethodTableMap::COL_FK_GLOSSARY_KEY_METHOD_NAME
                    => $method->getGlossaryKeyName()->getKey(),
                SpyShipmentMethodTableMap::COL_FK_GLOSSARY_KEY_METHOD_DESCRIPTION
                    => $method->getGlossaryKeyDescription()->getKey(),
                SpyShipmentMethodTableMap::COL_PRICE => $method->getPrice(),
                SpyShipmentMethodTableMap::COL_IS_ACTIVE
                    => $item[SpyShipmentMethodTableMap::COL_IS_ACTIVE],
                self::ACTIONS => ''
                ];
        }
        unset($queryResults);

        return $results;
    }
}
