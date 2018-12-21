<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\CartsRestApi\Business\CartsRestApiBusinessFactory;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeBridge;
use Spryker\Zed\Quote\Business\QuoteFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CartsRestApi
 * @group Business
 * @group Facade
 * @group CartsRestApiFacadeTest
 * Add your own group annotations below this line
 */
class CartsRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CartsRestApi\CartsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCartsFacadeWillFindQuoteByUuid(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $actualQuoteResponseTransfer = $cartsRestApiFacade->findQuoteByUuid($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $actualQuoteResponseTransfer);
        $this->assertNotNull($actualQuoteResponseTransfer->getQuoteTransfer());
        $this->assertInstanceOf(QuoteTransfer::class, $actualQuoteResponseTransfer->getQuoteTransfer());
        $this->assertEquals($quoteTransfer->getCustomerReference(), $actualQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference());
        $this->assertEquals($quoteTransfer->getUuid(), $actualQuoteResponseTransfer->getQuoteTransfer()->getUuid());
    }

    /**
     * @return void
     */
    public function testCartsFacadeWillNotFindQuoteByUuidWithoutCustomerReference(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getFailingMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransferWithoutCustomerReference();
        $actualQuoteResponseTransfer = $cartsRestApiFacade->findQuoteByUuid($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $actualQuoteResponseTransfer);
        $this->assertNull($actualQuoteResponseTransfer->getQuoteTransfer());
    }

    /**
     * @return void
     */
    public function testCartsFacadeWillNotFindQuoteByUuidWithoutCartUuid(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getFailingMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransferWithoutCartUuid();
        $actualQuoteResponseTransfer = $cartsRestApiFacade->findQuoteByUuid($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $actualQuoteResponseTransfer);
        $this->assertNull($actualQuoteResponseTransfer->getQuoteTransfer());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockCartsRestApiBusinessFactory(): MockObject
    {
        $cartsRestApiBusinessFactoryMock = $this->createPartialMock(
            CartsRestApiBusinessFactory::class,
            ['getQuoteFacade']
        );

        $cartsRestApiBusinessFactoryMock = $this->addMockQuoteFacade($cartsRestApiBusinessFactoryMock);

        return $cartsRestApiBusinessFactoryMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $cartsRestApiBusinessFactoryMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockQuoteFacade(MockObject $cartsRestApiBusinessFactoryMock): MockObject
    {
        $quoteFacadeMock = $this->createPartialMock(
            QuoteFacade::class,
            [
                'findQuoteByUuid',
                'findQuoteByCustomer',
            ]
        );

        $quoteFacadeMock->method('findQuoteByUuid')
            ->willReturn($this->tester->prepareQuoteResponseTransferWithQuote());

        $quoteFacadeMock->method('findQuoteByCustomer')
            ->willReturn($this->tester->prepareQuoteResponseTransferWithQuote());

        $cartsRestApiBusinessFactoryMock->method('getQuoteFacade')
            ->willReturn((new CartsRestApiToQuoteFacadeBridge($quoteFacadeMock)));

        return $cartsRestApiBusinessFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getFailingMockCartsRestApiBusinessFactory(): MockObject
    {
        $cartsRestApiBusinessFactoryMock = $this->createPartialMock(
            CartsRestApiBusinessFactory::class,
            ['getQuoteFacade']
        );

        $cartsRestApiBusinessFactoryMock = $this->addFailingMockQuoteFacade($cartsRestApiBusinessFactoryMock);

        return $cartsRestApiBusinessFactoryMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $cartsRestApiBusinessFactoryMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addFailingMockQuoteFacade(MockObject $cartsRestApiBusinessFactoryMock): MockObject
    {
        $quoteFacadeMock = $this->createPartialMock(
            QuoteFacade::class,
            [
                'findQuoteByUuid',
                'findQuoteByCustomer',
            ]
        );

        $quoteFacadeMock->method('findQuoteByUuid')
            ->willReturn($this->tester->prepareQuoteResponseTransferWithoutQuote());

        $quoteFacadeMock->method('findQuoteByCustomer')
            ->willReturn($this->tester->prepareQuoteResponseTransferWithQuote());

        $cartsRestApiBusinessFactoryMock->method('getQuoteFacade')
            ->willReturn((new CartsRestApiToQuoteFacadeBridge($quoteFacadeMock)));

        return $cartsRestApiBusinessFactoryMock;
    }
}
