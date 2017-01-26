<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Checkout\DataContainer;

use Generated\Shared\Transfer\QuoteTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Yves\Checkout\DataContainer\DataContainer;
use Spryker\Yves\Checkout\Dependency\Client\CheckoutToQuoteInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group Checkout
 * @group DataContainer
 * @group DataContainerTest
 */
class DataContainerTest extends PHPUnit_Framework_TestCase
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
     * @return \PHPUnit_Framework_MockObject_MockObject|CheckoutToQuoteInterface
     */
    private function getQuoteClientMock()
    {
        return $this->getMockBuilder(CheckoutToQuoteInterface::class)->getMock();
    }

}
