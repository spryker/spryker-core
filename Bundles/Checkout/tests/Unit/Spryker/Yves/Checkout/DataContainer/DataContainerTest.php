<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Checkout\DataContainer;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\CartClientInterface;
use Spryker\Yves\Checkout\DataContainer\DataContainer;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group Checkout
 * @group DataContainer
 * @group DataContainerTest
 */
class DataContainerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGetShouldCallClientToGetQuoteTransferIfNotSet()
    {
        $cartClientMock = $this->getCartClientMock();
        $cartClientMock->expects($this->once())->method('getQuote')->willReturn(new QuoteTransfer());

        $dataContainer = new DataContainer($cartClientMock);

        $this->assertInstanceOf(QuoteTransfer::class, $dataContainer->get());
    }

    /**
     * @return void
     */
    public function testGetShouldNotCallClientToGetQuoteTransferIfSet()
    {
        $cartClientMock = $this->getCartClientMock();
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
        $cartClientMock = $this->getCartClientMock();
        $cartClientMock->expects($this->once())->method('storeQuote');
        $dataContainer = new DataContainer($cartClientMock);
        $dataContainer->set(new QuoteTransfer());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\Cart\CartClientInterface
     */
    private function getCartClientMock()
    {
        return $this->getMockBuilder(CartClientInterface::class)->getMock();
    }

}
