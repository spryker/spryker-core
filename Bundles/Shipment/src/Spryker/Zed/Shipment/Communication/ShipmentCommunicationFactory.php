<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Shipment\Communication\Form\CarrierForm;
use Spryker\Zed\Shipment\Communication\Form\DataProvider\CarrierFormDataProvider;
use Spryker\Zed\Shipment\Communication\Form\DataProvider\MethodFormDataProvider;
use Spryker\Zed\Shipment\Communication\Form\MethodForm;
use Spryker\Zed\Shipment\Communication\Table\MethodTable;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface getQueryContainer()
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

        return new MethodTable($methodQuery, $this->getMoneyFacade());
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCarrierForm(array $formData, array $formOptions = [])
    {
        $form = new CarrierForm();

        return $this->getFormFactory()->create($form, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\Shipment\Communication\Form\DataProvider\MethodFormDataProvider
     */
    public function createMethodFormDataProvider()
    {
        return new MethodFormDataProvider(
            $this->getQueryContainer(),
            $this->getTaxFacade(),
            $this->getPlugins(),
            $this->getMoneyFacade(),
            $this->getStore()
        );
    }

    /**
     * @return array
     */
    protected function getPlugins()
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToMoneyInterface
     */
    protected function getMoneyFacade()
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::FACADE_MONEY);
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createMethodForm(array $data, array $options = [])
    {
        $form = new MethodForm();

        return $this->getFormFactory()->create($form, $data, $options);
    }

    /**
     * @return \Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface
     */
    public function getTaxFacade()
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::FACADE_TAX);
    }

    /**
     * @return \Spryker\Zed\Shipment\Communication\Form\DataProvider\CarrierFormDataProvider
     */
    public function createCarrierFormDataProvider()
    {
        return new CarrierFormDataProvider($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::STORE);
    }
}
