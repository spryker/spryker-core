<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\QuoteApproval;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Client\QuoteApproval\Checker\QuoteApprovalChecker;
use Spryker\Client\QuoteApproval\QuoteApprovalConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group QuoteApproval
 * @group QuoteApprovalCheckerTest
 * Add your own group annotations below this line
 */
class QuoteApprovalCheckerTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\QuoteApproval\Checker\QuoteApprovalChecker
     */
    protected $quoteApprovalCheckerMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->quoteApprovalCheckerMock = $this->createQuoteApprovalCheckerMock();
    }

    /**
     * @return void
     */
    public function testIsQuoteApplicableForApprovalProcessChecksFullFilledQuoteTransfer(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withBillingAddress()
            ->withShippingAddress()
            ->withPayment()
            ->withShipment()
            ->build();

        // Act
        $isQuoteApplicableForApproval = $this->quoteApprovalCheckerMock->isQuoteApplicableForApprovalProcess($quoteTransfer);

        // Assert
        $this->assertTrue($isQuoteApplicableForApproval);
    }

    /**
     * @return void
     */
    public function testIsQuoteApplicableForApprovalProcessChecksNotFilledQuoteTransfer(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())->build();

        // Act
        $isQuoteApplicableForApproval = $this->quoteApprovalCheckerMock->isQuoteApplicableForApprovalProcess($quoteTransfer);

        // Assert
        $this->assertFalse($isQuoteApplicableForApproval);
    }

    /**
     * @return void
     */
    public function testIsQuoteApplicableForApprovalProcessChecksQuoteTransferWithoutRequiredField(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withBillingAddress()
            ->withShippingAddress()
            ->withPayment()
            ->build();

        // Act
        $isQuoteApplicableForApproval = $this->quoteApprovalCheckerMock->isQuoteApplicableForApprovalProcess($quoteTransfer);

        // Assert
        $this->assertFalse($isQuoteApplicableForApproval);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteApprovalCheckerMock(): MockObject
    {
        return $this->getMockBuilder(QuoteApprovalChecker::class)
            ->setConstructorArgs([
                $this->createQuoteApprovalConfigMock(),
            ])
            ->setMethods(null)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQuoteApprovalConfigMock(): MockObject
    {
        $quoteApprovalConfigMock = $this->getMockBuilder(QuoteApprovalConfig::class)
            ->setMethods(['getRequiredQuoteFields'])
            ->disableOriginalConstructor()
            ->getMock();

        $quoteApprovalConfigMock
            ->method('getRequiredQuoteFields')
            ->willReturn([
                QuoteTransfer::BILLING_ADDRESS,
                QuoteTransfer::SHIPPING_ADDRESS,
                QuoteTransfer::PAYMENTS,
                QuoteTransfer::SHIPMENT,
            ]);

        return $quoteApprovalConfigMock;
    }
}
