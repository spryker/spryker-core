<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteApprovalShipmentConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Price\PriceConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group QuoteApprovalShipmentConnector
 * @group Business
 * @group Facade
 * @group QuoteApprovalShipmentConnectorFacadeTest
 * Add your own group annotations below this line
 */
class QuoteApprovalShipmentConnectorFacadeTest extends Unit
{
    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @uses \Spryker\Shared\QuoteApproval\QuoteApprovalConfig::STATUS_APPROVED
     */
    protected const QUOTE_APPROVAL_STATUS_APPROVED = 'approved';

    /**
     * @var \SprykerTest\Zed\QuoteApprovalShipmentConnector\QuoteApprovalShipmentConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetQuoteFieldsAllowedForSavingReturnsShipmentFieldsForApprovedQuoteWithSingleShipment(): void
    {
        //Arrange
        $quoteTransfer = (new QuoteBuilder([
                QuoteTransfer::PRICE_MODE => PriceConfig::PRICE_MODE_NET,
            ]))
            ->withShippingAddress()
            ->withShipment([ShipmentTransfer::SHIPMENT_SELECTION => 'custom'])
            ->build()
            ->addExpense((new ExpenseTransfer())->setType(static::SHIPMENT_EXPENSE_TYPE))
            ->addQuoteApproval((new QuoteApprovalTransfer())->setStatus(static::QUOTE_APPROVAL_STATUS_APPROVED));

        //Act
        $quoteFields = $this->tester->getFacade()->getQuoteFieldsAllowedForSaving($quoteTransfer);

        //Assert
        $this->assertSame($quoteFields, [
            QuoteTransfer::SHIPMENT,
            QuoteTransfer::SHIPPING_ADDRESS,
        ]);
    }

    /**
     * @return void
     */
    public function testGetQuoteFieldsAllowedForSavingReturnsEmptyArrayForApprovedQuoteWithMultiShipment(): void
    {
        //Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->addQuoteApproval((new QuoteApprovalTransfer())->setStatus(static::QUOTE_APPROVAL_STATUS_APPROVED));

        //Act
        $quoteFields = $this->tester->getFacade()->getQuoteFieldsAllowedForSaving($quoteTransfer);

        //Assert
        $this->assertCount(0, $quoteFields);
    }

    /**
     * @return void
     */
    public function testGetQuoteFieldsAllowedForSavingReturnsEmptyArrayForQuoteWithoutApproval(): void
    {
        //Arrange
        $quoteTransfer = (new QuoteTransfer());

        //Act
        $quoteFields = $this->tester->getFacade()->getQuoteFieldsAllowedForSaving($quoteTransfer);

        //Assert
        $this->assertCount(0, $quoteFields);
    }
}
