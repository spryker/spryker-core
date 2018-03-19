<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Payment\PaymentType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Shipment\ShipmentType;

class PaymentDataProvider
{

    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToPaymentFacadeInterface
     */
    protected $paymentFacade;

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToPaymentFacadeInterface $paymentFacade
     */
    public function __construct($paymentFacade)
    {
        $this->paymentFacade = $paymentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(QuoteTransfer $quoteTransfer)
    {
        return [
            'data_class' => QuoteTransfer::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            PaymentType::OPTION_PAYMENT_METHODS_ARRAY => $this->getShipmentMethodList($quoteTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(QuoteTransfer $quoteTransfer)
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
        $shipmentMethodsTransfer = $this->paymentFacade->getAvailableMethods($quoteTransfer);
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
