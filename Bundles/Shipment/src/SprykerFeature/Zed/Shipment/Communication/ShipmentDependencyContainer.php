<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication;

use SprykerFeature\Zed\Shipment\Communication\Form\MethodForm;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Shipment\Communication\Form\CarrierForm;
use SprykerFeature\Zed\Shipment\Communication\Table\MethodTable;
use SprykerFeature\Zed\Shipment\Persistence\ShipmentQueryContainer;
use SprykerFeature\Zed\Shipment\ShipmentDependencyProvider;
use SprykerFeature\Zed\Tax\Persistence\TaxQueryContainerInterface;

/**
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

        return new MethodTable($methodQuery);
    }

    /**
     * @return CarrierForm
     */
    public function createCarrierForm()
    {
        $carrierQuery = $this->getQueryContainer()->queryCarriers();

        return new CarrierForm($carrierQuery);
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

        return new MethodForm(
                $methodQuery,
                $carrierQuery,
                $taxSetQuery,
                $this->getConfig(),
                $this->getProvidedDependency(ShipmentDependencyProvider::PLUGINS),
                $idMethod
            );
    }

    /**
     * @return TaxQueryContainerInterface
     */
    protected function getTaxQueryContainer()
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::QUERY_CONTAINER_TAX);
    }

}
