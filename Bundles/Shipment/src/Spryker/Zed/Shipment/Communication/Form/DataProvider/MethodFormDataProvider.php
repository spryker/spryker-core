<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Shipment\Business\ShipmentFacadeInterface;
use Spryker\Zed\Shipment\Communication\Form\MethodForm;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToMoneyInterface;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;

class MethodFormDataProvider
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface
     */
    protected $shipmentQueryContainer;

    /**
     * @var array
     */
    protected $plugins;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface
     */
    protected $taxFacade;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $shipmentQueryContainer
     * @param array $plugins
     * @param \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface $shipmentFacade
     * @param \Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface $taxFacade
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToMoneyInterface $moneyFacade
     */
    public function __construct(
        ShipmentQueryContainerInterface $shipmentQueryContainer,
        array $plugins,
        ShipmentFacadeInterface $shipmentFacade,
        ShipmentToTaxInterface $taxFacade,
        ShipmentToMoneyInterface $moneyFacade
    ) {
        $this->shipmentQueryContainer = $shipmentQueryContainer;
        $this->plugins = $plugins;
        $this->shipmentFacade = $shipmentFacade;
        $this->taxFacade = $taxFacade;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param int|null $idMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function getData($idMethod = null)
    {
        if ($idMethod !== null) {
            $shipmentMethodEntity = $this->shipmentQueryContainer
                ->queryMethodWithMethodPricesAndCarrierById($idMethod)
                ->find()
                ->getFirst();
            $shipmentMethodTransfer = $this->shipmentFacade->transformShipmentMethodEntityToShipmentMethodTransfer($shipmentMethodEntity);

            return $shipmentMethodTransfer;
        }

        return new ShipmentMethodTransfer();
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $options = [
            MethodForm::OPTION_CARRIER_CHOICES => $this->getCarrierOptions(),
            MethodForm::OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST => $this->getPluginOptions(ShipmentDependencyProvider::AVAILABILITY_PLUGINS),
            MethodForm::OPTION_PRICE_PLUGIN_CHOICE_LIST => $this->getPluginOptions(ShipmentDependencyProvider::PRICE_PLUGINS),
            MethodForm::OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST => $this->getPluginOptions(ShipmentDependencyProvider::DELIVERY_TIME_PLUGINS),
            MethodForm::OPTION_TAX_SETS => $this->createTaxSetsList(),
        ];

        $options[MethodForm::OPTION_MONEY_FACADE] = $this->moneyFacade;
        $options[MethodForm::OPTION_DATA_CLASS] = ShipmentMethodTransfer::class;

        return $options;
    }

    /**
     * @return array
     */
    protected function createTaxSetsList()
    {
        $taxSetCollection = $this->taxFacade->getTaxSets();

        $taxSetList = [];
        foreach ($taxSetCollection->getTaxSets() as $taxSetTransfer) {
            $taxSetList[$taxSetTransfer->getIdTaxSet()] = $taxSetTransfer->getName();
        }

        return $taxSetList;
    }

    /**
     * @return array
     */
    protected function getCarrierOptions()
    {
        $carriers = $this->shipmentQueryContainer->queryCarriers()->filterByIsActive(true)->find();
        $result = [];

        foreach ($carriers as $carrier) {
            $result[$carrier->getIdShipmentCarrier()] = $carrier->getName();
        }

        return $result;
    }

    /**
     * @param string $pluginsType
     *
     * @return array
     */
    private function getPluginOptions($pluginsType)
    {
        $plugins = array_keys($this->plugins[$pluginsType]);

        return array_combine($plugins, $plugins);
    }
}
