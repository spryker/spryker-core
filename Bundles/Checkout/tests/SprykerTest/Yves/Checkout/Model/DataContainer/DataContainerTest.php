<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Checkout\Model\DataContainer;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Checkout\DataContainer\DataContainer;
use Spryker\Yves\Checkout\Dependency\Client\CheckoutToQuoteInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Checkout
 * @group Model
 * @group DataContainer
 * @group DataContainerTest
 * Add your own group annotations below this line
 */
class DataContainerTest extends Unit
{
    /**
     * @return void
     */
    public function testGetShouldCallClientToGetQuoteTransferIfNotSet()
    {
        $quoteClientMock = $this->getQuoteClientMock();
        $quoteClientMock->expects($this->once())->method('getQuote')->willReturn(new QuoteTransfer());

        $dataContainer = new DataContainer($quoteClientMock);

        $this->assertInstanceOf(QuoteTransfer::class, $dataContainer->get());
    }

    /**
     * @return void
     */
    public function testGetShouldNotCallClientToGetQuoteTransferIfSet()
    {
        $cartClientMock = $this->getQuoteClientMock();
        $cartClientMock->expects($this->once())->method('getQuote')->willReturn(new QuoteTransfer());
        $dataContainer = new DataContainer($cartClientMock);
        $dataContainer->get();

        $cartClientMock->expects($this->never())->method('getQuote');
        $dataContainer->get();
    }

    /**
     * @return void
     */
    public function testSetShouldCallClientToStoreQuoteTransfer()
    {
        $cartClientMock = $this->getQuoteClientMock();
        $cartClientMock->expects($this->once())->method('setQuote');
        $dataContainer = new DataContainer($cartClientMock);
        $dataContainer->set(new QuoteTransfer());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\Checkout\Dependency\Client\CheckoutToQuoteInterface
     */
    private function getQuoteClientMock()
    {
        return $this->getMockBuilder(CheckoutToQuoteInterface::class)->getMock();
    }
}
