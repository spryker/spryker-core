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
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Spryker\Zed\MultiCart\Business\MultiCartBusinessFactory;
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
    protected const COLLECTION_DATA = [
        [
            'id_quote' => 1,
            'name' => 'Shopping cart',
            'store' => 'DE',
            'priceMode' => 'GROSS_MODE',
            'currency' => 'EUR',
            'customerReference' => 'tester-de',
            'uuid' => '7fd5cc11-87ff-55e2-b413-7e07f9640404',

        ],
        [
            'id_quote' => 2,
            'name' => 'test quote two',
            'store' => 'DE',
            'priceMode' => 'GROSS_MODE',
            'currency' => 'EUR',
            'customerReference' => 'tester-de',
            'uuid' => '22b43a18-e46c-55bf-bc00-65f4dee0727a',
        ],
    ];

    protected const CUSTOMER_DATA = [
        'customer_reference' => 'tester-de',
        'email' => 'tester@test.com',
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
     * @var \SprykerTest\Zed\MultiCart\MultiCartBusinessTester
     */
    protected $tester;

    /**
     * @var \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    protected $customer;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->customer = $this->createCustomer();
        $this->multiCartFacade = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testReadCartsCollectionFromDatabaseByCustomer()
    {
        $this->initWithResult();
        $criteriaTransfer = new QuoteCriteriaFilterTransfer();
        $criteriaTransfer->setCustomerReference(static::CUSTOMER_DATA['customer_reference']);

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
        $criteriaTransfer->setCustomerReference(static::CUSTOMER_DATA['customer_reference']);

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

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    protected function createCustomer()
    {
        $customerEntity = SpyCustomerQuery::create()->findOneByCustomerReference(static::CUSTOMER_DATA['customer_reference']);
        if ($customerEntity) {
            return $customerEntity;
        }
        $customerEntity = new SpyCustomer();
        $customerEntity->fromArray(static::CUSTOMER_DATA);
        $customerEntity->save();

        return $customerEntity;
    }
}
