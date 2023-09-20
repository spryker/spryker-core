<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteRequestBuilder;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use SprykerTest\Zed\QuoteRequest\QuoteRequestBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group QuoteRequest
 * @group Business
 * @group Facade
 * @group CreateQuoteRequestForCompanyUserTest
 * Add your own group annotations below this line
 */
class CreateQuoteRequestForCompanyUserTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\QuoteRequest\QuoteRequestBusinessTester
     */
    protected QuoteRequestBusinessTester $tester;

    /**
     * @return void
     */
    public function testCreateUserQuoteRequestCreatesUserQuoteRequest(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $quoteRequestTransfer = (new QuoteRequestBuilder())->build()
            ->setCompanyUser($this->tester->createCompanyUser($customerTransfer));

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->createQuoteRequestForCompanyUser($quoteRequestTransfer);
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertSame(SharedQuoteRequestConfig::STATUS_IN_PROGRESS, $storedQuoteRequestTransfer->getStatus());
        $this->assertNotNull($storedQuoteRequestTransfer->getLatestVersion());
        $this->assertSame(
            $quoteRequestTransfer->getCompanyUser()->getIdCompanyUser(),
            $storedQuoteRequestTransfer->getCompanyUser()->getIdCompanyUser(),
        );
    }

    /**
     * @return void
     */
    public function testCreateUserQuoteRequestThrowsExceptionWithEmptyCompanyUser(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder())->build();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createQuoteRequestForCompanyUser($quoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreateUserQuoteRequestThrowsExceptionWithEmptyIdCompanyUser(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder())->build()
            ->setCompanyUser(new CompanyUserTransfer());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createQuoteRequestForCompanyUser($quoteRequestTransfer);
    }
}
