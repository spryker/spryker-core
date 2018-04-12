<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Plugin;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Shipment\ShipmentType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class ShipmentManualOrderEntryFormPlugin extends AbstractPlugin implements ManualOrderEntryFormPluginInterface
{
    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    public function __construct()
    {
        $this->shipmentFacade = $this->getFactory()->getShipmentFacade();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return ShipmentType::TYPE_NAME;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $dataTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(Request $request, $dataTransfer = null): FormInterface
    {
        return $this->getFactory()->createShipmentForm($request, $dataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handleData($quoteTransfer, &$form, Request $request): AbstractTransfer
    {
        $idShipmentMethod = $quoteTransfer->getIdShipmentMethod();
        if ($idShipmentMethod) {
            $shipmentMethodTransfer = $this->shipmentFacade->findAvailableMethodById($idShipmentMethod, $quoteTransfer);
            $shipmentTransfer = new ShipmentTransfer();
            $shipmentTransfer->setMethod($shipmentMethodTransfer);

            $quoteTransfer->setShipment($shipmentTransfer);

            $expenseTransfer = $this->createShippingExpenseTransfer($shipmentMethodTransfer);
            $quoteTransfer->addExpense($expenseTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $dataTransfer
     *
     * @return bool
     */
    public function isPreFilled($dataTransfer = null): bool
    {
        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createShippingExpenseTransfer(ShipmentMethodTransfer $shipmentMethodTransfer): ExpenseTransfer
    {
        $shipmentExpenseTransfer = new ExpenseTransfer();
        $shipmentExpenseTransfer->fromArray($shipmentMethodTransfer->toArray(), true);
        $shipmentExpenseTransfer->setType(ShipmentConstants::SHIPMENT_EXPENSE_TYPE);
        $this->setPrice($shipmentExpenseTransfer, $shipmentMethodTransfer->getStoreCurrencyPrice());
        $shipmentExpenseTransfer->setQuantity(1);

        return $shipmentExpenseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $shipmentExpenseTransfer
     * @param int $price
     *
     * @return void
     */
    protected function setPrice(ExpenseTransfer $shipmentExpenseTransfer, $price): void
    {
        $shipmentExpenseTransfer->setUnitNetPrice(0);
        $shipmentExpenseTransfer->setUnitGrossPrice($price);
    }
}
