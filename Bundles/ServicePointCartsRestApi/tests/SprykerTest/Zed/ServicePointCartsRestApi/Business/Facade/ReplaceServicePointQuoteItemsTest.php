<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointCartsRestApi\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteReplacementResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestShipmentsTransfer;
use Spryker\Zed\ServicePointCartsRestApi\Dependency\Facade\ServicePointCartsRestApiToServicePointCartFacadeInterface;
use Spryker\Zed\ServicePointCartsRestApi\ServicePointCartsRestApiDependencyProvider;
use SprykerTest\Zed\ServicePointCartsRestApi\ServicePointCartsRestApiBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointCartsRestApi
 * @group Business
 * @group Facade
 * @group ReplaceServicePointQuoteItemsTest
 * Add your own group annotations below this line
 */
class ReplaceServicePointQuoteItemsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ServicePointCartsRestApi\ServicePointCartsRestApiBusinessTester
     */
    protected ServicePointCartsRestApiBusinessTester $tester;

    /**
     * @return void
     */
    public function testReplacesSuccessfully(): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();
        $quoteReplacementResponseTransfer = (new QuoteReplacementResponseTransfer())->setQuote($quoteTransfer);
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesTransfer())
            ->addShipment(new RestShipmentsTransfer());

        $this->mockServicePointCartFacade($quoteReplacementResponseTransfer, 1);

        // Act
        $this->tester->getFacade()->replaceServicePointQuoteItems($restCheckoutRequestAttributesTransfer, $quoteTransfer);
    }

    /**
     * @return void
     */
    public function testShouldNotReplaceWhenShipmentsAreNotDefined(): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesTransfer());
        $quoteReplacementResponseTransfer = (new QuoteReplacementResponseTransfer())->setQuote($quoteTransfer);

        $this->mockServicePointCartFacade($quoteReplacementResponseTransfer, 0);

        // Act
        $this->tester->getFacade()->replaceServicePointQuoteItems($restCheckoutRequestAttributesTransfer, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteReplacementResponseTransfer $quoteReplacementResponseTransfer
     * @param int $callCount
     *
     * @return void
     */
    protected function mockServicePointCartFacade(QuoteReplacementResponseTransfer $quoteReplacementResponseTransfer, int $callCount): void
    {
        $servicePointCartFacadeMock = $this->getMockBuilder(ServicePointCartsRestApiToServicePointCartFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $servicePointCartFacadeMock->expects($this->exactly($callCount))
            ->method('replaceQuoteItems')
            ->willReturn($quoteReplacementResponseTransfer);

        $this->tester->setDependency(
            ServicePointCartsRestApiDependencyProvider::FACADE_SERVICE_POINT_CART,
            $servicePointCartFacadeMock,
        );
    }
}
