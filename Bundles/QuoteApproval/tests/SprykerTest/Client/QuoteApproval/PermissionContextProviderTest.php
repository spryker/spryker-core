<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\QuoteApproval\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Client\QuoteApproval\Permission\ContextProvider\PermissionContextProvider;
use Spryker\Client\QuoteApproval\QuoteApprovalConfig;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig as SharedQuoteApprovalConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group QuoteApproval
 * @group Business
 * @group PermissionContextProviderTest
 * Add your own group annotations below this line
 */
class PermissionContextProviderTest extends Unit
{
    protected const QUOTE_GRAND_TOTAL = 12345;
    protected const QUOTE_SHIPMENT_PRICE = 200;

    /**
     * @return void
     */
    public function testProvideContextShouldReturnGrandTotalInCentContextElement(): void
    {
        // Assign
        $quoteTransfer = $this->buildQuoteTransfer();

        $permissionContextProvider = new PermissionContextProvider(
            $this->getMockedZedConfig(true)
        );

        // Act
        $context = $permissionContextProvider->provideContext($quoteTransfer);

        // Assert
        $this->assertArrayHasKey(SharedQuoteApprovalConfig::PERMISSION_CONTEXT_CENT_AMOUNT, $context);
        $this->assertEquals(static::QUOTE_GRAND_TOTAL, $context[SharedQuoteApprovalConfig::PERMISSION_CONTEXT_CENT_AMOUNT]);
    }

    /**
     * @return void
     */
    public function testProvideContextShouldReturnGrandTotalWithoutShipmentInCentContextElement(): void
    {
        // Assign
        $quoteTransfer = $this->buildQuoteTransfer();

        $permissionContextProvider = new PermissionContextProvider(
            $this->getMockedZedConfig(false)
        );

        // Act
        $context = $permissionContextProvider->provideContext($quoteTransfer);

        // Assert
        $this->assertArrayHasKey(SharedQuoteApprovalConfig::PERMISSION_CONTEXT_CENT_AMOUNT, $context);
        $this->assertEquals(static::QUOTE_GRAND_TOTAL - static::QUOTE_SHIPMENT_PRICE, $context[SharedQuoteApprovalConfig::PERMISSION_CONTEXT_CENT_AMOUNT]);
    }

    /**
     * @param bool $isPermissionCalculationIncludeShipment
     *
     * @return \Spryker\Client\QuoteApproval\QuoteApprovalConfig
     */
    protected function getMockedZedConfig(bool $isPermissionCalculationIncludeShipment): QuoteApprovalConfig
    {
        /** @var \Spryker\Client\QuoteApproval\QuoteApprovalConfig $quoteApprovalConfigMock */
        $quoteApprovalConfigMock = $this->getMockBuilder(QuoteApprovalConfig::class)
            ->setMethods(['getRequiredQuoteFieldsForApprovalProcess', 'isShipmentPriceIncludedInQuoteApprovalPermissionCheck'])
            ->disableOriginalConstructor()
            ->getMock();

        $quoteApprovalConfigMock
            ->method('isShipmentPriceIncludedInQuoteApprovalPermissionCheck')
            ->willReturn($isPermissionCalculationIncludeShipment);

        return $quoteApprovalConfigMock;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function buildQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteBuilder())
            ->withStore()
            ->withCurrency()
            ->withTotals([TotalsTransfer::GRAND_TOTAL => static::QUOTE_GRAND_TOTAL])
            ->withShipment(
                (new ShipmentBuilder())
                    ->withMethod([ShipmentMethodTransfer::STORE_CURRENCY_PRICE => static::QUOTE_SHIPMENT_PRICE])
            )
            ->build();
    }
}
