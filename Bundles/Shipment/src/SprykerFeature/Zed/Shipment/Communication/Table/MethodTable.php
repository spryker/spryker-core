<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Table;

use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\Shipment\Persistence\Propel\ShipmentMethodQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class MethodTable extends AbstractTable
{
    const CARRIER = 'carrier';

    /**
     * @var ShipmentMethodQuery
     */
    protected $methodQuery;

    /**
     * @param ShipmentMethodQuery $methodQuery
     */
    public function __construct(ShipmentMethodQuery $methodQuery)
    {
        $this->customerQuery = $methodQuery;
    }

    /**
     * @param TableConfiguration $config
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([ 'isActive' => 'Active']);
        $config->setUrl('table');

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return ObjectCollection
     */
    protected function prepareData(TableConfiguration $config)
    {

    }

    /**
     * @param $details
     *
     * @return array|string
     */
    private function buildLinks($details)
    {

    }
}
