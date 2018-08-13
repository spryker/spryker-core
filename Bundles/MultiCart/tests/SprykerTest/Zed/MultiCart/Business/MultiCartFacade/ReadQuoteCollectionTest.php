<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MultiCart\Business\MultiCartFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Spryker\Zed\MultiCart\Business\MultiCartBusinessFactory;
use Spryker\Zed\MultiCart\Business\MultiCartFacade;
use Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeBridge;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group MultiCart
 * @group Business
 * @group MultiCartFacade
 * @group ReadQuoteCollectionTest
 * Add your own group annotations below this line
 */
class ReadQuoteCollectionTest extends Unit
{
    protected const DE_1 = 'DE--1';
    protected const COLLECTION_DATA = [
        [
            'id_quote' => 1,
            'name' => 'Shopping cart',
            'store' => 'DE',
            'priceMode' => 'GROSS_MODE',
            'currency' => 'EUR',
            'customerReference' => 'DE--1',
            'uuid' => '67c4148a-d677-53bd-9324-f1567e09ae56',

        ],
        [
            'id_quote' => 2,
            'name' => 'test quote two',
            'store' => 'DE',
            'priceMode' => 'GROSS_MODE',
            'currency' => 'EUR',
            'customerReference' => 'DE--1',
            'uuid' => '907c0644-0f53-53bb-bc4e-30274011641f',
        ],
    ];

    /**
     * @var \Spryker\Zed\MultiCart\Business\MultiCartBusinessFactory
     */
    protected $multiCartBusinessFactory;

    /**
     * @var \Spryker\Zed\MultiCart\Business\MultiCartFacade
     */
    protected $multiCartFacade;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->multiCartFacade = new MultiCartFacade();

        return parent::setUp();
    }

    /**
     * @return void
     */
    public function testReadCartsCollectionFromDatabaseByCustomer()
    {
        $this->initWithResult();
        $criteriaTransfer = new QuoteCriteriaFilterTransfer();
        $criteriaTransfer->setCustomerReference(static::DE_1);

        $quoteResponseTransfer = $this->multiCartFacade->getQuoteCollectionByCriteria($criteriaTransfer);

        $this->assertNotEmpty($quoteResponseTransfer->getQuotes());
        $expectedTransfer = $this->getQuotesCollectionTransfer();
        $this->assertEquals($expectedTransfer->getQuotes(), $quoteResponseTransfer->getQuotes());
    }

    /**
     * @return void
     */
    public function testReadEmptyCartsCollectionFromDatabaseByCustomer()
    {
        $this->initWithoutResult();
        $criteriaTransfer = new QuoteCriteriaFilterTransfer();
        $criteriaTransfer->setCustomerReference(static::DE_1);

        $quoteResponseTransfer = $this->multiCartFacade->getQuoteCollectionByCriteria($criteriaTransfer);

        $this->assertEmpty($quoteResponseTransfer->getQuotes());
        $expectedTransfer = $this->getQuotesCollectionTransfer();
        $this->assertNotEquals($expectedTransfer->getQuotes(), $quoteResponseTransfer->getQuotes());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject | \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeBridge
     */
    protected function getQuoteFacadeMock()
    {
        $quoteCollectionTransfer = $this->getQuotesCollectionTransfer();
        $mock = $this
            ->createMock(MultiCartToQuoteFacadeBridge::class);
        $mock->method('getQuoteCollection')
            ->willReturn($quoteCollectionTransfer);

        return $mock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject | \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeBridge
     */
    protected function getEmptyQuoteFacadeMock()
    {
        $quoteCollectionTransfer = new QuoteCollectionTransfer();
        $mock = $this
            ->createMock(MultiCartToQuoteFacadeBridge::class);
        $mock->method('getQuoteCollection')
            ->willReturn($quoteCollectionTransfer);

        return $mock;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    protected function getQuotesCollectionTransfer(): QuoteCollectionTransfer
    {
        $quoteCollectionTransfer = (new QuoteCollectionTransfer())
            ->setQuotes(
                new ArrayObject(static::COLLECTION_DATA)
            );
        return $quoteCollectionTransfer;
    }

    /**
     * @return void
     */
    protected function initWithResult(): void
    {
        $builder = $this->getMockBuilder(MultiCartBusinessFactory::class);
        $builder->setMethods(
            [
                'getQuoteFacade',
            ]
        );

        $this->multiCartBusinessFactory = $builder->getMock();
        $this->multiCartBusinessFactory->method('getQuoteFacade')
            ->willReturn($this->getQuoteFacadeMock());

        $this->multiCartFacade->setFactory($this->multiCartBusinessFactory);
    }

    /**
     * @return void
     */
    protected function initWithoutResult(): void
    {
        $builder = $this->getMockBuilder(MultiCartBusinessFactory::class);
        $builder->setMethods(
            [
                'getQuoteFacade',
            ]
        );

        $this->multiCartBusinessFactory = $builder->getMock();
        $this->multiCartBusinessFactory->method('getQuoteFacade')
            ->willReturn($this->getEmptyQuoteFacadeMock());

        $this->multiCartFacade->setFactory($this->multiCartBusinessFactory);
    }
}
