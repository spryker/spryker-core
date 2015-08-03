<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\ShipmentCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Shipment\Communication\Form\CarrierForm;
use SprykerFeature\Zed\Shipment\Communication\Table\MethodTable;
use SprykerFeature\Zed\Shipment\Persistence\ShipmentQueryContainer;
use SprykerFeature\Zed\Shipment\ShipmentDependencyProvider;

/**
 * @method ShipmentCommunication getFactory()
 * @method ShipmentQueryContainer getQueryContainer()
 */
class ShipmentDependencyContainer extends AbstractCommunicationDependencyContainer
{
    /**
     * @return MethodTable
     */
    public function createMethodTable()
    {
        $methodQuery = $this->getQueryContainer()->queryMethods();

        return $this->getFactory()->createTableMethodTable($methodQuery);
    }

    /**
     * @return CarrierForm
     */
    public function createCarrierForm()
    {
        $carrierQuery = $this->getQueryContainer()->queryCarriers();

        return $this->getFactory()->createFormCarrierForm($carrierQuery);
    }

    /**
     * @param int|null $idMethod
     *
     * @return CarrierForm
     * @throws \ErrorException
     */
    public function createMethodForm($idMethod = null)
    {
        $methodQuery = $this->getQueryContainer()->queryMethods();
        $carrierQuery = $this->getQueryContainer()->queryCarriers();

        return $this
            ->getFactory()
            ->createFormMethodForm(
                $methodQuery,
                $carrierQuery,
                $this->getProvidedDependency(ShipmentDependencyProvider::PLUGINS),
                $idMethod
            )
            ;
    }
}
