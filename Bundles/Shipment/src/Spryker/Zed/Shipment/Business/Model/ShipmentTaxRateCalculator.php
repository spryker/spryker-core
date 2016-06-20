<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainer;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

class ShipmentTaxRateCalculator implements CalculatorInterface
{

    /**
     * @var ShipmentQueryContainerInterface
     */
    protected $shipmentQueryContainer;

    /**
     * ShipmentTaxRateCalculator constructor.
     *
     * @param ShipmentQueryContainerInterface $shipmentQueryContainer
     */
    public function __construct(ShipmentQueryContainerInterface $shipmentQueryContainer)
    {
        $this->shipmentQueryContainer = $shipmentQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getShipment() === null) {
            return;
        }

        //TODO: Fix Me with Tax Default class!!!
        $taxRate = 19.00;

        $taxSetEntity = $this->shipmentQueryContainer->queryTaxSetByShipmentMethodAndCountry(
            $quoteTransfer->getShipment()->getMethod()->getIdShipmentMethod(),
            $quoteTransfer->getBillingAddress()->getIso2Code()
        )->findOne();

        if ($taxSetEntity !== null) {
            $taxRate = (float) $taxSetEntity[ShipmentQueryContainer::COL_SUM_TAX_RATE];
        }

        $this->setShipmentTaxRate($quoteTransfer, $taxRate);
        $this->setQuoteExpenseTaxRate($quoteTransfer, $taxRate);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param float $taxRate
     *
     * @return void
     */
    protected function setQuoteExpenseTaxRate(QuoteTransfer $quoteTransfer, $taxRate)
    {
        foreach ($quoteTransfer->getExpenses() as $expense) {
            if ($expense->getType() === ShipmentConstants::SHIPMENT_EXPENSE_TYPE && $expense->getName() === $quoteTransfer->getShipment()
                    ->getMethod()
                    ->getName()
            ) {
                $expense->setTaxRate($taxRate);
            }
        }
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param float $taxRate
     *
     * @return void
     */
    protected function setShipmentTaxRate(QuoteTransfer $quoteTransfer, $taxRate)
    {
        $quoteTransfer->getShipment()
            ->getMethod()
            ->setTaxRate($taxRate)
        ;
    }
}
