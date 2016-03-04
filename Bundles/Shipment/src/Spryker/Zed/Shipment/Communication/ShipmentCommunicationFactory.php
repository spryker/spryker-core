<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication;

use Spryker\Zed\Shipment\Communication\Form\MethodForm;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Shipment\Communication\Form\CarrierForm;
use Spryker\Zed\Shipment\Communication\Form\MethodForm;
use Spryker\Zed\Shipment\Communication\Table\MethodTable;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainer getQueryContainer()
 * @method \Spryker\Zed\Shipment\ShipmentConfig getConfig()
 */
class ShipmentCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\Shipment\Communication\Table\MethodTable
     */
    public function createMethodTable()
    {
        $methodQuery = $this->getQueryContainer()->queryMethods();

        return new MethodTable($methodQuery);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
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
     * @return \Symfony\Component\Form\FormInterface
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
                $this->getProvidedDependency(ShipmentDependencyProvider::PLUGINS),
                $idMethod
            );

        return $this->createForm($form);
    }

    /**
     * @return \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface
     */
    protected function getTaxQueryContainer()
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::QUERY_CONTAINER_TAX);
    }

}
