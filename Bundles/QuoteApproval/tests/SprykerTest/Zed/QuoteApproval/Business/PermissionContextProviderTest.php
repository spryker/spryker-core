<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteApproval\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Zed\QuoteApproval\Business\Permission\ContextProvider\PermissionContextProvider;
use Spryker\Zed\QuoteApproval\QuoteApprovalConfig as QuoteApprovalZedConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
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
        $this->assertArrayHasKey(QuoteApprovalConfig::PERMISSION_CONTEXT_CENT_AMOUNT, $context);
        $this->assertEquals(static::QUOTE_GRAND_TOTAL, $context[QuoteApprovalConfig::PERMISSION_CONTEXT_CENT_AMOUNT]);
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
        $this->assertArrayHasKey(QuoteApprovalConfig::PERMISSION_CONTEXT_CENT_AMOUNT, $context);
        $this->assertEquals(static::QUOTE_GRAND_TOTAL - static::QUOTE_SHIPMENT_PRICE, $context[QuoteApprovalConfig::PERMISSION_CONTEXT_CENT_AMOUNT]);
    }

    /**
     * @param bool $isPermissionCalculationIncludeShipment
     *
     * @return \Spryker\Zed\QuoteApproval\QuoteApprovalConfig
     */
    protected function getMockedZedConfig(bool $isPermissionCalculationIncludeShipment): QuoteApprovalZedConfig
    {
        /** @var \Spryker\Zed\QuoteApproval\QuoteApprovalConfig $quoteApprovalConfigMock */
        $quoteApprovalConfigMock = $this->getMockBuilder(QuoteApprovalZedConfig::class)
            ->setMethods(['isShipmentPriceIncludedInQuoteApprovalPermissionCheck'])
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
