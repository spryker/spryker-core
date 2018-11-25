<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\CartsRestApi\Business\CartsRestApiBusinessFactory;
use Spryker\Zed\CartsRestApi\Communication\Plugin\QuoteCollectionReader\SingleQuoteCollectionReaderPlugin;
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
    public function testCartsFacadeWillReturnQuoteCollectionWithSingleQuoteByCriteria(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $actualQuoteCollectionTransfer = $cartsRestApiFacade->getSingleQuoteCollectionByCriteria($this->tester->prepareQuoteCriteriaFilterTransfer());

        $this->assertInstanceOf(QuoteCollectionTransfer::class, $actualQuoteCollectionTransfer);
        $this->assertCount(1, $actualQuoteCollectionTransfer->getQuotes());
        $this->assertInstanceOf(QuoteTransfer::class, $actualQuoteCollectionTransfer->getQuotes()->offsetGet(0));
        $this->assertEquals($this->tester::TEST_QUOTE_UUID, $actualQuoteCollectionTransfer->getQuotes()->offsetGet(0)->getUuid());
    }

    /**
     * @return void
     */
    public function testCartsFacadeWillReturnEmptyCollectionByCriteriaWithoutCustomerReference(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $actualQuoteCollectionTransfer = $cartsRestApiFacade->getSingleQuoteCollectionByCriteria($this->tester->prepareEmptyQuoteCriteriaFilterTransfer());

        $this->assertInstanceOf(QuoteCollectionTransfer::class, $actualQuoteCollectionTransfer);
        $this->assertCount(0, $actualQuoteCollectionTransfer->getQuotes());
    }

    /**
     * @return void
     */
    public function testCartsFacadeWillFindCustomerQuoteByUuid(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $actualQuoteResponseTransfer = $cartsRestApiFacade->findCustomerQuoteByUuid($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $actualQuoteResponseTransfer);
        $this->assertNotNull($actualQuoteResponseTransfer->getQuoteTransfer());
        $this->assertInstanceOf(QuoteTransfer::class, $actualQuoteResponseTransfer->getQuoteTransfer());
        $this->assertEquals($quoteTransfer->getCustomerReference(), $actualQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference());
        $this->assertEquals($quoteTransfer->getUuid(), $actualQuoteResponseTransfer->getQuoteTransfer()->getUuid());
    }

    /**
     * @return void
     */
    public function testCartsFacadeWillNotFindCustomerQuoteByUuidWithoutCustomerReference(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getFailingMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransferWithoutCustomerReference();
        $actualQuoteResponseTransfer = $cartsRestApiFacade->findCustomerQuoteByUuid($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $actualQuoteResponseTransfer);
        $this->assertNull($actualQuoteResponseTransfer->getQuoteTransfer());
    }

    /**
     * @return void
     */
    public function testCartsFacadeWillNotFindCustomerQuoteByUuidWithoutCartUuid(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getFailingMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransferWithoutCartUuid();
        $actualQuoteResponseTransfer = $cartsRestApiFacade->findCustomerQuoteByUuid($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $actualQuoteResponseTransfer);
        $this->assertNull($actualQuoteResponseTransfer->getQuoteTransfer());
    }

    /**
     * @return void
     */
    public function testCartsFacadeWillReturnQuoteCollectionWithQuoteByCriteria(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $actualQuoteCollectionTransfer = $cartsRestApiFacade->getQuoteCollectionByCriteria($this->tester->prepareQuoteCriteriaFilterTransfer());

        $this->assertInstanceOf(QuoteCollectionTransfer::class, $actualQuoteCollectionTransfer);
        $this->assertCount(1, $actualQuoteCollectionTransfer->getQuotes());
        $this->assertInstanceOf(QuoteTransfer::class, $actualQuoteCollectionTransfer->getQuotes()->offsetGet(0));
        $this->assertEquals($this->tester::TEST_QUOTE_UUID, $actualQuoteCollectionTransfer->getQuotes()->offsetGet(0)->getUuid());
    }

    /**
     * @return void
     */
    public function testCartsFacadeWillNotReturnCollectionByCriteriaWithoutCustomerReference(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getFailingMockCartsRestApiBusinessFactory());

        $actualQuoteCollectionTransfer = $cartsRestApiFacade->getQuoteCollectionByCriteria($this->tester->prepareEmptyQuoteCriteriaFilterTransfer());

        $this->assertInstanceOf(QuoteCollectionTransfer::class, $actualQuoteCollectionTransfer);
        $this->assertCount(0, $actualQuoteCollectionTransfer->getQuotes());
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
                'getQuoteCollectionReaderPlugin',
            ]
        );

        $cartsRestApiBusinessFactoryMock = $this->addMockQuoteFacade($cartsRestApiBusinessFactoryMock);
        $cartsRestApiBusinessFactoryMock = $this->addMockQuoteCollectionReaderPlugin($cartsRestApiBusinessFactoryMock);

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
     * @param \PHPUnit\Framework\MockObject\MockObject $cartsRestApiBusinessFactoryMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockQuoteCollectionReaderPlugin(MockObject $cartsRestApiBusinessFactoryMock): MockObject
    {
        $singleQuoteCollectionReaderPlugin = $this->createPartialMock(
            SingleQuoteCollectionReaderPlugin::class,
            [
                'getQuoteCollectionByCriteria',
            ]
        );

        $singleQuoteCollectionReaderPlugin->method('getQuoteCollectionByCriteria')
            ->willReturn($this->tester->prepareQuoteCollectionTransfer());

        $cartsRestApiBusinessFactoryMock->method('getQuoteCollectionReaderPlugin')
            ->willReturn($singleQuoteCollectionReaderPlugin);

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
                'getQuoteCollectionReaderPlugin',
            ]
        );

        $cartsRestApiBusinessFactoryMock = $this->addFailingMockQuoteFacade($cartsRestApiBusinessFactoryMock);
        $cartsRestApiBusinessFactoryMock = $this->addFailingMockQuoteCollectionReaderPlugin($cartsRestApiBusinessFactoryMock);

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

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $cartsRestApiBusinessFactoryMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addFailingMockQuoteCollectionReaderPlugin(MockObject $cartsRestApiBusinessFactoryMock): MockObject
    {
        $singleQuoteCollectionReaderPlugin = $this->createPartialMock(
            SingleQuoteCollectionReaderPlugin::class,
            [
                'getQuoteCollectionByCriteria',
            ]
        );

        $singleQuoteCollectionReaderPlugin->method('getQuoteCollectionByCriteria')
            ->willReturn($this->tester->prepareEmptyQuoteCollectionTransfer());

        $cartsRestApiBusinessFactoryMock->method('getQuoteCollectionReaderPlugin')
            ->willReturn($singleQuoteCollectionReaderPlugin);

        return $cartsRestApiBusinessFactoryMock;
    }
}
