<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Communication;

use Spryker\Zed\Shipment\Communication\Form\MethodForm;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Shipment\Communication\Form\CarrierForm;
use Spryker\Zed\Shipment\Communication\Table\MethodTable;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainer;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;
use Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface;
use Spryker\Zed\Shipment\ShipmentConfig;

/**
 * @method ShipmentQueryContainer getQueryContainer()
 * @method ShipmentConfig getConfig()
 */
class ShipmentCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return MethodTable
     */
    public function createMethodTable()
    {
        $methodQuery = $this->getQueryContainer()->queryMethods();

        return new MethodTable($methodQuery);
    }

    /**
     * @return CarrierForm
     */
    public function createCarrierForm()
    {
        $carrierQuery = $this->getQueryContainer()->queryCarriers();

        $form = new CarrierForm($carrierQuery);

        return $this->createForm($form);
    }

    /**
     * @param int|null $idMethod
     *
     * @throws \ErrorException
     *
     * @return CarrierForm
     */
    public function createMethodForm($idMethod = null)
    {
        $methodQuery = $this->getQueryContainer()->queryMethods();
        $carrierQuery = $this->getQueryContainer()->queryCarriers();

        $taxSetQuery = $this->getTaxQueryContainer()->queryAllTaxSets();

        $form = new MethodForm(
                $methodQuery,
                $carrierQuery,
                $taxSetQuery,
                $this->getConfig(),
                $this->getProvidedDependency(ShipmentDependencyProvider::PLUGINS),
                $idMethod
            );

        return $this->createForm($form);
    }

    /**
     * @return TaxQueryContainerInterface
     */
    protected function getTaxQueryContainer()
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::QUERY_CONTAINER_TAX);
    }

}
