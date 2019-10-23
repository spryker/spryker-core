<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Provider;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToTaxFacadeInterface;

class ShipmentMethodFormDataProvider extends ViewShipmentMethodFormDataProvider
{
    public const OPTION_CARRIER_CHOICES = 'carrier_choices';
    public const OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST = 'availability_plugin_choice_list';
    public const OPTION_PRICE_PLUGIN_CHOICE_LIST = 'price_plugin_choice_list';
    public const OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST = 'delivery_time_plugin_choice_list';
    public const OPTION_MONEY_FACADE = 'money facade';

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
        return $shipmentMethodTransfer->setStoreRelation(new StoreRelationTransfer());
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        $options = [
            static::OPTION_CARRIER_CHOICES => $this->getCarrierOptions(),
            static::OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST => $this->getPluginOptions(static::KEY_AVAILABILITY),
            static::OPTION_PRICE_PLUGIN_CHOICE_LIST => $this->getPluginOptions(static::KEY_PRICE),
            static::OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST => $this->getPluginOptions(static::KEY_DELIVERY_TIME),
        ];

        $options = array_merge(parent::getOptions(), $options);
        $options[static::OPTION_PRICES_DISABLED] = false;
        $options[static::OPTION_STORE_RELATION_DISABLED] = false;
        $options[static::OPTION_TAX_SET_DISABLED] = false;

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
     * @param string $pluginsType
     *
     * @return string[]
     */
    private function getPluginOptions($pluginsType): array
    {
        $plugins = array_keys($this->shipmentFacade->getShipmentMethodPlugins()[$pluginsType]);

        return array_combine($plugins, $plugins);
    }
}
