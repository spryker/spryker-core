<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesQuoteRequestConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;
use SprykerTest\Zed\SalesQuoteRequestConnector\SalesQuoteRequestConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesQuoteRequestConnector
 * @group Business
 * @group Facade
 * @group SaveOrderQuoteRequestVersionReferenceTest
 * Add your own group annotations below this line
 */
class SaveOrderQuoteRequestVersionReferenceTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_QUOTE_REQUEST_VERSION_REFERENCE = 'fake-quote-request-reference';

    /**
     * @var \SprykerTest\Zed\SalesQuoteRequestConnector\SalesQuoteRequestConnectorBusinessTester
     */
    protected SalesQuoteRequestConnectorBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testShouldPersistQuoteRequestVersionReferenceWhenQuoteRequestVersionExists(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setQuoteRequestVersionReference(static::FAKE_QUOTE_REQUEST_VERSION_REFERENCE);
        $saveOrderTransfer = $this->tester->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        // Act
        $this->tester->getFacade()->saveOrderQuoteRequestVersionReference($quoteTransfer, $saveOrderTransfer);

        // Assert
        $this->assertSame(
            static::FAKE_QUOTE_REQUEST_VERSION_REFERENCE,
            $this->tester->findOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder())->getQuoteRequestVersionReference(),
        );
    }

    /**
     * @return void
     */
    public function testShouldNotPersistQuoteRequestVersionReferenceWhenQuoteRequestVersionNotExists(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        // Act
        $this->tester->getFacade()->saveOrderQuoteRequestVersionReference(new QuoteTransfer(), $saveOrderTransfer);

        // Assert
        $this->assertNull(
            $this->tester->findOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder())->getQuoteRequestVersionReference(),
        );
    }

    /**
     * @return void
     */
    public function testThrowsNullValueExceptionWhenRequiredIdOrderPropertiesIsNotSet(): void
    {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "idSalesOrder" of transfer `Generated\Shared\Transfer\SaveOrderTransfer` is null.');

        // Act
        $this->tester->getFacade()->saveOrderQuoteRequestVersionReference(
            (new QuoteTransfer())->setQuoteRequestVersionReference(static::FAKE_QUOTE_REQUEST_VERSION_REFERENCE),
            new SaveOrderTransfer(),
        );
    }
}
