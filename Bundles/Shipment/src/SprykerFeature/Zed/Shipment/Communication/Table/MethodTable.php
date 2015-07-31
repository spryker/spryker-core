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
    const PLUGINS = 'Plugins';
    const ID_METHOD_PARAMETER = 'id_method';

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
            SpyShipmentMethodTableMap::COL_IS_ACTIVE => '',
            SpyShipmentMethodTableMap::COL_FK_SHIPMENT_CARRIER => self::CARRIER,
            SpyShipmentMethodTableMap::COL_NAME => self::METHOD,
            SpyShipmentMethodTableMap::COL_GLOSSARY_KEY_DESCRIPTION => self::DESCRIPTION,
            SpyShipmentMethodTableMap::COL_PRICE => self::PRICE,
            self::PLUGINS => self::PLUGINS,
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
                SpyShipmentMethodTableMap::COL_IS_ACTIVE =>
                    '<span class="label '
                    . (($method->isActive()) ? 'label-success">Activated' : 'label-danger">Disabled')  . '</span>',
                SpyShipmentMethodTableMap::COL_FK_SHIPMENT_CARRIER => $method->getShipmentCarrier()->getName(),
                SpyShipmentMethodTableMap::COL_NAME => $method->getName(),
                SpyShipmentMethodTableMap::COL_GLOSSARY_KEY_DESCRIPTION => $method->getGlossaryKeyDescription(),
                SpyShipmentMethodTableMap::COL_PRICE => $method->getPrice(),
                self::PLUGINS => 'Availability ' . $method->getAvailabilityPlugin() .
                    ' | Price ' . $method->getPriceCalculationPlugin() .
                    ' | Delivery ' . $method->getDeliveryTimePlugin(),
                self::ACTIONS =>
                    '<div class="btn-group btn-group-sm" role="group">' .
                    '<a class="btn btn-outline btn-default" href="/shipment/method/edit?' . self::ID_METHOD_PARAMETER . '='
                    . $item[SpyShipmentMethodTableMap::COL_ID_SHIPMENT_METHOD] . '"><i class="fa fa-paste"></i> Edit</a>' .
                    '<a class="btn btn-outline  btn-default" href="/shipment/method/delete?' . self::ID_METHOD_PARAMETER . '='
                    . $item[SpyShipmentMethodTableMap::COL_ID_SHIPMENT_METHOD] . '"><i class="fa fa-times"></i> Delete</a>' .
                    '</div>'

            ];
        }

        return $results;
    }
}
