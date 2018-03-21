<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\QuoteTransfer;
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
    public function getOptions($quoteTransfer)
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
    public function getData($quoteTransfer)
    {
        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getShipmentMethodList(QuoteTransfer $quoteTransfer)
    {
        if (!$quoteTransfer->getStore() || !$quoteTransfer->getCurrency()) {
            return [];
        }
        $shipmentMethodsTransfer = $this->shipmentFacade->getAvailableMethods($quoteTransfer);
        $shipmentMethodList = [];

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
}
