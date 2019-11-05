<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ShipmentMethodPluginSelectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToTaxFacadeInterface;

class ShipmentMethodFormDataProvider extends ViewShipmentMethodFormDataProvider
{
    public const OPTION_CARRIER_CHOICES = 'carrier_choices';
    public const OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST = 'availability_plugin_choice_list';
    public const OPTION_PRICE_PLUGIN_CHOICE_LIST = 'price_plugin_choice_list';
    public const OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST = 'delivery_time_plugin_choice_list';
    public const OPTION_DELIVERY_KEY_DISABLED = 'option_delivery_key_disabled';

    protected const KEY_AVAILABILITY = 'AVAILABILITY_PLUGINS';
    protected const KEY_PRICE = 'PRICE_PLUGINS';
    protected const KEY_DELIVERY_TIME = 'DELIVERY_TIME_PLUGINS';

    /**
     * @var \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface $shipmentFacade
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToTaxFacadeInterface $taxFacade
     */
    public function __construct(
        ShipmentGuiToShipmentFacadeInterface $shipmentFacade,
        ShipmentGuiToTaxFacadeInterface $taxFacade
    ) {
        parent::__construct($taxFacade);
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function getData(ShipmentMethodTransfer $shipmentMethodTransfer): ShipmentMethodTransfer
    {
        return $shipmentMethodTransfer;
    }

    /**
     * @param bool $isDeliveryKeyDisabled
     *
     * @return array
     */
    public function getOptions(bool $isDeliveryKeyDisabled = false): array
    {
        $shipmentMethodPluginSelectionTransfer = $this->shipmentFacade->getShipmentMethodPlugins();

        $options = [
            static::OPTION_CARRIER_CHOICES => $this->getCarrierOptions(),
            static::OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST => $this->getAvailabilityPluginOptions($shipmentMethodPluginSelectionTransfer),
            static::OPTION_PRICE_PLUGIN_CHOICE_LIST => $this->getPricePluginOptions($shipmentMethodPluginSelectionTransfer),
            static::OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST => $this->getDeliveryTimePluginOptions($shipmentMethodPluginSelectionTransfer),
        ];

        $options = array_merge(parent::getOptions(), $options);
        $options[static::OPTION_PRICES_DISABLED] = false;
        $options[static::OPTION_STORE_RELATION_DISABLED] = false;
        $options[static::OPTION_TAX_SET_DISABLED] = false;
        $options[static::OPTION_DELIVERY_KEY_DISABLED] = $isDeliveryKeyDisabled;

        return $options;
    }

    /**
     * @return string[]
     */
    protected function getCarrierOptions(): array
    {
        $shipmentCarriers = $this->shipmentFacade->getActiveShipmentCarriers();
        $result = [];

        foreach ($shipmentCarriers as $shipmentCarrierTransfer) {
            $result[$shipmentCarrierTransfer->getIdShipmentCarrier()] = $shipmentCarrierTransfer->getName();
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodPluginSelectionTransfer $shipmentMethodPluginSelectionTransfer
     *
     * @return string[]
     */
    protected function getAvailabilityPluginOptions(ShipmentMethodPluginSelectionTransfer $shipmentMethodPluginSelectionTransfer): array
    {
        $availabilityPluginOptions = $shipmentMethodPluginSelectionTransfer->getAvailabilityPluginOptions();

        return array_combine($availabilityPluginOptions, $availabilityPluginOptions);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodPluginSelectionTransfer $shipmentMethodPluginSelectionTransfer
     *
     * @return string[]
     */
    protected function getPricePluginOptions(ShipmentMethodPluginSelectionTransfer $shipmentMethodPluginSelectionTransfer): array
    {
        $pricePluginOptions = $shipmentMethodPluginSelectionTransfer->getPricePluginOptions();

        return array_combine($pricePluginOptions, $pricePluginOptions);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodPluginSelectionTransfer $shipmentMethodPluginSelectionTransfer
     *
     * @return array
     */
    protected function getDeliveryTimePluginOptions(ShipmentMethodPluginSelectionTransfer $shipmentMethodPluginSelectionTransfer): array
    {
        $deliveryTimePluginOptions = $shipmentMethodPluginSelectionTransfer->getDeliveryTimePluginOptions();

        return array_combine($deliveryTimePluginOptions, $deliveryTimePluginOptions);
    }
}
