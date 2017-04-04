<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainer;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

class ShipmentTaxRateCalculator implements CalculatorInterface
{

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface
     */
    protected $shipmentQueryContainer;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface
     */
    protected $taxFacade;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $shipmentQueryContainer
     * @param \Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface $taxFacade
     */
    public function __construct(ShipmentQueryContainerInterface $shipmentQueryContainer, ShipmentToTaxInterface $taxFacade)
    {
        $this->shipmentQueryContainer = $shipmentQueryContainer;
        $this->taxFacade = $taxFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getShipment() === null || $quoteTransfer->getShipment()->getMethod() === null) {
            return;
        }

        $taxRate = $this->taxFacade->getDefaultTaxRate();
        $taxSetEntity = $this->findTaxSetByIdShipmentMethod($quoteTransfer);

        if ($taxSetEntity !== null) {
            $taxRate = (float)$taxSetEntity[ShipmentQueryContainer::COL_MAX_TAX_RATE];
        }

        $this->setShipmentTaxRate($quoteTransfer, $taxRate);
        $this->setQuoteExpenseTaxRate($quoteTransfer, $taxRate);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param float $taxRate
     *
     * @return void
     */
    protected function setQuoteExpenseTaxRate(QuoteTransfer $quoteTransfer, $taxRate)
    {
        foreach ($quoteTransfer->getExpenses() as $expense) {
            if ($expense->getType() === ShipmentConstants::SHIPMENT_EXPENSE_TYPE &&
                $expense->getName() === $quoteTransfer->getShipment()->getMethod()->getName()
            ) {
                $expense->setTaxRate($taxRate);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param float $taxRate
     *
     * @return void
     */
    protected function setShipmentTaxRate(QuoteTransfer $quoteTransfer, $taxRate)
    {
        $quoteTransfer->getShipment()
            ->getMethod()
            ->setTaxRate($taxRate);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethod|null
     */
    protected function findTaxSetByIdShipmentMethod(QuoteTransfer $quoteTransfer)
    {
        $countryIsoCode = null;
        if ($quoteTransfer->getShippingAddress()) {
            $countryIsoCode = $quoteTransfer->getShippingAddress()->getIso2Code();
        }

        if (!$countryIsoCode) {
            $countryIsoCode = $this->taxFacade->getDefaultTaxCountryIso2Code();
        }

        return $this->shipmentQueryContainer->queryTaxSetByIdShipmentMethodAndCountryIso2Code(
            $quoteTransfer->getShipment()->getMethod()->getIdShipmentMethod(),
            $countryIsoCode
        )->findOne();
    }

}
