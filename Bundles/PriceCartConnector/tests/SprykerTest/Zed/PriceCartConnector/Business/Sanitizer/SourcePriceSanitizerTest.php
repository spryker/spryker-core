<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceCartConnector\Business\Sanitizer;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\PriceCartConnector\Business\Sanitizer\SourcePriceSanitizer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group PriceCartConnector
 * @group Business
 * @group Sanitizer
 * @group SourcePriceSanitizerTest
 * Add your own group annotations below this line
 */
class SourcePriceSanitizerTest extends Unit
{
    /**
     * @var \Spryker\Zed\PriceCartConnector\Business\Sanitizer\SourcePriceSanitizer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $sourcePriceSanitizerMock;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->sourcePriceSanitizerMock = $this->createSourcePriceSanitizerMock();
    }

    /**
     * @return void
     */
    public function testSanitizeSourcePricesCleanUpSourcePricesInQuote(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(), ItemTransfer::UNIT_PRICE => 1, ItemTransfer::QUANTITY => 1])
            ->build();

        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteTransfer->getItems()->offsetGet(0);
        $itemTransfer->setSourceUnitGrossPrice(2)
            ->setSourceUnitNetPrice(1);

        // Act
        $updatedQuoteTransfer = $this->sourcePriceSanitizerMock->sanitizeSourcePrices($quoteTransfer);
        /** @var \Generated\Shared\Transfer\ItemTransfer $updatedItemTransfer */
        $updatedItemTransfer = $updatedQuoteTransfer->getItems()->offsetGet(0);

        // Assert
        $this->assertNull($updatedItemTransfer->getSourceUnitGrossPrice());
        $this->assertNull($updatedItemTransfer->getSourceUnitNetPrice());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSourcePriceSanitizerMock(): MockObject
    {
        return $this->getMockBuilder(SourcePriceSanitizer::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
    }
}
