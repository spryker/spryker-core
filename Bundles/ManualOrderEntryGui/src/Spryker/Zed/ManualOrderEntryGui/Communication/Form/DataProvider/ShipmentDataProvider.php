<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Shipment\ShipmentType;

class ShipmentDataProvider implements FormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToShipmentFacadeInterface $shipmentFacade
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMoneyFacadeInterface $moneyFacade
     */
    public function __construct($shipmentFacade, $moneyFacade)
    {
        $this->shipmentFacade = $shipmentFacade;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions($quoteTransfer): array
    {
        return [
            'data_class' => QuoteTransfer::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            ShipmentType::OPTION_SHIPMENT_METHODS_ARRAY => $this->getShipmentMethodList($quoteTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData($quoteTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getShipment() === null) {
            $quoteTransfer->setShipment(new ShipmentTransfer());
        }
        if ($quoteTransfer->getShipment()->getMethod() === null) {
            $quoteTransfer->getShipment()->setMethod(new ShipmentMethodTransfer());
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getShipmentMethodList(QuoteTransfer $quoteTransfer): array
    {
        if (!$quoteTransfer->getStore() || !$quoteTransfer->getCurrency()) {
            return [];
        }

        $shipmentMethodList = [];
        $shipmentMethodsTransfer = $this->resolveShipmentMethods($quoteTransfer);
        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
            $moneyTransfer = $this->moneyFacade->fromInteger($shipmentMethodTransfer->getStoreCurrencyPrice(), $shipmentMethodTransfer->getCurrencyIsoCode());

            $row = $shipmentMethodTransfer->getCarrierName()
                . ' - '
                . $shipmentMethodTransfer->getName()
                . ': '
                . $this->moneyFacade->formatWithSymbol($moneyTransfer);

            $shipmentMethodList[$shipmentMethodTransfer->getIdShipmentMethod()] = $row;
        }

        return $shipmentMethodList;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    protected function resolveShipmentMethods(QuoteTransfer $quoteTransfer): ShipmentMethodsTransfer
    {
        if ($this->hasItemLevelShipments($quoteTransfer)) {
            $shipmentMethodsCollectionTransfer = $this->shipmentFacade->getAvailableMethodsByShipment($quoteTransfer);

            return current($shipmentMethodsCollectionTransfer->getShipmentMethods());
        }

        return $this->shipmentFacade->getAvailableMethods($quoteTransfer);
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasItemLevelShipments(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null) {
                return false;
            }
        }

        return true;
    }
}
