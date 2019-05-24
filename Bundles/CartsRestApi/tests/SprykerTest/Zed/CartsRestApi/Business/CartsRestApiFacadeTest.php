<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\CartsRestApi\Business\CartsRestApiBusinessFactory;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeBridge;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface;
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

        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->findQuoteByUuid($quoteTransfer);
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

        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->findQuoteByUuid($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testCreateQuoteWillCreateQuote(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $quoteTransfer->setCustomer((new CustomerTransfer())->setCustomerReference($quoteTransfer->getCustomerReference()));

        $quoteResponseTransfer = $cartsRestApiFacade->createQuote($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateQuoteWillNotCreateQuoteWithOutCustomer(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->createQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testCreateSingleQuoteWillNotAllowCreateMoreThanOneQuote(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $quoteResponseTransfer = $cartsRestApiFacade->createSingleQuote($quoteTransfer);

        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockCartsRestApiBusinessFactory(): MockObject
    {
        $cartsRestApiBusinessFactoryMock = $this->createPartialMock(
            CartsRestApiBusinessFactory::class,
            [
                'getQuoteFacade',
                'getStoreFacade',
                'getCartFacade',
                'getPersistentCartFacade',
                'getQuoteCreatorPlugin',
            ]
        );

        $cartsRestApiBusinessFactoryMock = $this->addMockQuoteFacade($cartsRestApiBusinessFactoryMock);
        $cartsRestApiBusinessFactoryMock = $this->addQuoteCreatorPlugin($cartsRestApiBusinessFactoryMock);

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
            ]
        );

        $quoteFacadeMock->method('findQuoteByUuid')
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
            [
                'getQuoteFacade',
                'getStoreFacade',
                'getCartFacade',
                'getPersistentCartFacade',
                'getQuoteCreatorPlugin',
            ]
        );

        $cartsRestApiBusinessFactoryMock = $this->addFailingMockQuoteFacade($cartsRestApiBusinessFactoryMock);
        $cartsRestApiBusinessFactoryMock = $this->addQuoteCreatorPlugin($cartsRestApiBusinessFactoryMock);

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
            ]
        );

        $quoteFacadeMock->method('findQuoteByUuid')
            ->willReturn($this->tester->prepareQuoteResponseTransferWithoutQuote());

        $cartsRestApiBusinessFactoryMock->method('getQuoteFacade')
            ->willReturn((new CartsRestApiToQuoteFacadeBridge($quoteFacadeMock)));

        return $cartsRestApiBusinessFactoryMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $cartsRestApiBusinessFactoryMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addQuoteCreatorPlugin(MockObject $cartsRestApiBusinessFactoryMock): MockObject
    {
        $cartsRestApiBusinessFactoryMock->method('getQuoteCreatorPlugin')
            ->willReturn($this->createMockQuoteCreatorPlugin());

        return $cartsRestApiBusinessFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMockQuoteCreatorPlugin(): MockObject
    {
        $mockQuoteCreatorPlugin = $this->createPartialMock(
            QuoteCreatorPluginInterface::class,
            ['createQuote']
        );
        $mockQuoteCreatorPlugin
            ->method('createQuote')
            ->willReturn($this->tester->prepareQuoteResponseTransferWithQuote());

        return $mockQuoteCreatorPlugin;
    }
}
