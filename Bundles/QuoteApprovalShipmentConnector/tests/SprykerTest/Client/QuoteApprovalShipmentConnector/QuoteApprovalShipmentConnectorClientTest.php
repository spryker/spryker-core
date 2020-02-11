<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\QuoteApprovalShipmentConnector;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group QuoteApprovalShipmentConnector
 * @group QuoteApprovalShipmentConnectorClientTest
 * Add your own group annotations below this line
 */
class QuoteApprovalShipmentConnectorClientTest extends Unit
{
    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @var \SprykerTest\Client\QuoteApprovalShipmentConnector\QuoteApprovalShipmentConnectorClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsQuoteShipmentApplicableForApprovalProcessReturnsTrueForCorrectQuoteWithMultiShipment(): void
    {
        //Arrange
        $quoteTransfer = $this->tester->createQuoteTransferWithMultiShipment();

        //Act
        $isQuoteShipmentApplicableForApprovalProcess = $this->tester->getClient()->isQuoteShipmentApplicableForApprovalProcess($quoteTransfer);

        //Assert
        $this->assertTrue($isQuoteShipmentApplicableForApprovalProcess);
    }

    /**
     * @return void
     */
    public function testIsQuoteShipmentApplicableForApprovalProcessReturnsTrueForCorrectQuoteWithSingleShipment(): void
    {
        //Arrange
        $quoteTransfer = $this->tester->createQuoteTransferWithSingleShipment();

        //Act
        $isQuoteShipmentApplicableForApprovalProcess = $this->tester->getClient()->isQuoteShipmentApplicableForApprovalProcess($quoteTransfer);

        //Assert
        $this->assertTrue($isQuoteShipmentApplicableForApprovalProcess);
    }

    /**
     * @return void
     */
    public function testIsQuoteShipmentApplicableForApprovalProcessReturnsFalseForQuoteWithoutShipment(): void
    {
        //Arrange
        $quoteTransfer = new QuoteTransfer();

        //Act
        $isQuoteShipmentApplicableForApprovalProcess = $this->tester->getClient()->isQuoteShipmentApplicableForApprovalProcess($quoteTransfer);

        //Assert
        $this->assertFalse($isQuoteShipmentApplicableForApprovalProcess);
    }
}
