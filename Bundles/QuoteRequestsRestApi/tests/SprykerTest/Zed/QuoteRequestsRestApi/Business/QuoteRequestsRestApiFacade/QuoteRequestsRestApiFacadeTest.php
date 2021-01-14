<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequestsRestApi\Business\QuoteRequestsRestApiFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestsRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group QuoteRequestsRestApi
 * @group Business
 * @group QuoteRequestsRestApiFacade
 * @group Facade
 * @group QuoteRequestsRestApiFacadeTest
 * Add your own group annotations below this line
 */
class QuoteRequestsRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\QuoteRequestsRestApi\QuoteRequestsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Spryker\Zed\QuoteRequestsRestApi\Business\QuoteRequestsRestApiFacadeInterface
     */
    protected $quoteRequestsRestApiFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $customerTransfer = $this->tester->haveCustomer();
        $itemTransfer = (new ItemBuilder([
            ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
            ItemTransfer::UNIT_PRICE => 1,
            ItemTransfer::QUANTITY => 1,
        ]))->build();
        $this->quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
            QuoteTransfer::CUSTOMER => $customerTransfer->toArray(),
            QuoteTransfer::ITEMS => [
                $itemTransfer->toArray(),
            ],
        ]);
        $companyUserTransfer = $this->tester->createCompanyUser($customerTransfer);
        $companyUserTransfer->setCustomer(null);
        $customerTransfer->setCompanyUserTransfer($companyUserTransfer);
        $this->customerTransfer = $customerTransfer;
        $this->quoteRequestsRestApiFacade = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testCreateQuoteRequestCreatesQuoteRequest(): void
    {
        // Arrange
        $quoteRequestsRequestTransfer = new QuoteRequestsRequestTransfer();
        $quoteRequestsRequestTransfer->setCartUuid($this->quoteTransfer->getUuid())
            ->setCustomer($this->customerTransfer);

        // Act
        $quoteRequestResponseTransfer = $this->quoteRequestsRestApiFacade->createQuoteRequest($quoteRequestsRequestTransfer);
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertNotNull($storedQuoteRequestTransfer->getIdQuoteRequest());
    }
}
