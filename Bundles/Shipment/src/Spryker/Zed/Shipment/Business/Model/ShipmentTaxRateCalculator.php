<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Shared\Shipment\ShipmentConfig;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainer;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

/**
 * @deprecated Use \Spryker\Zed\Shipment\Business\Calculator\ShipmentTaxRateCalculator instead.
 */
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
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $shipmentQueryContainer
     * @param \Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface $taxFacade
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     */
    public function __construct(
        ShipmentQueryContainerInterface $shipmentQueryContainer,
        ShipmentToTaxInterface $taxFacade,
        ShipmentServiceInterface $shipmentService
    ) {
        $this->shipmentQueryContainer = $shipmentQueryContainer;
        $this->taxFacade = $taxFacade;
        $this->shipmentService = $shipmentService;
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

        $taxRate = $this->getTaxRate($quoteTransfer);

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
        $shipmentMethodName = $quoteTransfer->requireShipment()
            ->getShipment()
            ->requireMethod()
            ->getMethod()
            ->getName();
        foreach ($quoteTransfer->getExpenses() as $expense) {
            if ($expense->getType() === ShipmentConfig::SHIPMENT_EXPENSE_TYPE && $expense->getName() === $shipmentMethodName) {
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
     * @return float
     */
    protected function getTaxRate(QuoteTransfer $quoteTransfer)
    {
        $taxSetEntity = $this->findTaxSet($quoteTransfer);
        if ($taxSetEntity !== null && isset($taxSetEntity[ShipmentQueryContainer::COL_MAX_TAX_RATE])) {
            return (float)$taxSetEntity[ShipmentQueryContainer::COL_MAX_TAX_RATE];
        }

        return $this->taxFacade->getDefaultTaxRate();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]|null
     */
    protected function findTaxSet(QuoteTransfer $quoteTransfer)
    {
        $countryIso2Code = $this->getCountryIso2Code($quoteTransfer);
        $idShipmentMethod = $quoteTransfer->requireShipment()
            ->getShipment()
            ->requireMethod()
            ->getMethod()
            ->getIdShipmentMethod();

        /**
         * @var string[]|null $taxSet
         */
        $taxSet = $this->shipmentQueryContainer
            ->queryTaxSetByIdShipmentMethodAndCountryIso2Code($idShipmentMethod, $countryIso2Code)
            ->findOne();

        return $taxSet;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getCountryIso2Code(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getShippingAddress()) {
            return $quoteTransfer->getShippingAddress()->getIso2Code();
        }

        return $this->taxFacade->getDefaultTaxCountryIso2Code();
    }
}
