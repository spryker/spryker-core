<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\Customer\Business\Checkout;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\Customer\Business\Validator\CustomerCheckoutSalutationValidator;
use Spryker\Zed\Customer\CustomerConfig;
use Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Checkout
 * @group CustomerCheckoutSalutationValidatorTest
 * Add your own group annotations below this line
 */
class CustomerCheckoutSalutationValidatorTest extends Unit
{
    /**
     * @var string
     */
    protected const VALID_SALUTATION = 'Mr';

    /**
     * @var string
     */
    protected const INVALID_SALUTATION = 'Invalid';

    /**
     * @var int
     */
    protected const ERROR_CODE = 4003;

    /**
     * @var string
     */
    protected const EXPECTED_ERROR_MESSAGE = 'customer.salutation.invalid';

    /**
     * @return void
     */
    public function testValidateWithValidSalutationReturnsTrue(): void
    {
        // Arrange
        $customerRepositoryMock = $this->createCustomerRepositoryMock();
        $customerRepositoryMock->method('getAllSalutations')
            ->willReturn([static::VALID_SALUTATION, 'Mrs', 'Ms']);

        $customerConfigMock = $this->createCustomerConfigMock();
        $customerConfigMock->method('getCustomerInvalidSalutationErrorCode')
            ->willReturn(static::ERROR_CODE);

        $validator = new CustomerCheckoutSalutationValidator($customerRepositoryMock, $customerConfigMock);

        $customerTransfer = (new CustomerBuilder([
            'salutation' => static::VALID_SALUTATION,
        ]))
        ->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withCustomer($customerTransfer->toArray())
            ->build();

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = $validator->validate($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($isValid);
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testValidateWithInvalidSalutationReturnsFalse(): void
    {
        // Arrange
        $customerRepositoryMock = $this->createCustomerRepositoryMock();
        $customerRepositoryMock->method('getAllSalutations')
            ->willReturn(['Mr', 'Mrs', 'Ms']);

        $customerConfigMock = $this->createCustomerConfigMock();
        $customerConfigMock->method('getCustomerInvalidSalutationErrorCode')
            ->willReturn(static::ERROR_CODE);

        $validator = new CustomerCheckoutSalutationValidator($customerRepositoryMock, $customerConfigMock);

        $customerTransfer = (new CustomerBuilder([
            'salutation' => static::INVALID_SALUTATION,
        ]))
        ->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withCustomer($customerTransfer->toArray())
            ->build();

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $result = $validator->validate($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($result);
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());

        $error = $checkoutResponseTransfer->getErrors()->getIterator()->current();
        $this->assertInstanceOf(CheckoutErrorTransfer::class, $error);
        $this->assertEquals(static::ERROR_CODE, $error->getErrorCode());
        $this->assertEquals(static::EXPECTED_ERROR_MESSAGE, $error->getMessage());
    }

    /**
     * @return void
     */
    public function testValidateWithEmptySalutationReturnsTrue(): void
    {
        // Arrange
        $customerRepositoryMock = $this->createCustomerRepositoryMock();
        $customerRepositoryMock->method('getAllSalutations')
            ->willReturn(['Mr', 'Mrs', 'Ms']);

        $customerConfigMock = $this->createCustomerConfigMock();
        $customerConfigMock->method('getCustomerInvalidSalutationErrorCode')
            ->willReturn(static::ERROR_CODE);

        $validator = new CustomerCheckoutSalutationValidator($customerRepositoryMock, $customerConfigMock);

        $customerTransfer = (new CustomerBuilder([
            'salutation' => '',
        ]))
        ->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withCustomer($customerTransfer->toArray())
            ->build();

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $result = $validator->validate($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($result);
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testValidateWithNullSalutationReturnsTrue(): void
    {
        // Arrange
        $customerRepositoryMock = $this->createCustomerRepositoryMock();
        $customerRepositoryMock->method('getAllSalutations')
            ->willReturn(['Mr', 'Mrs', 'Ms']);

        $customerConfigMock = $this->createCustomerConfigMock();
        $customerConfigMock->method('getCustomerInvalidSalutationErrorCode')
            ->willReturn(static::ERROR_CODE);

        $validator = new CustomerCheckoutSalutationValidator($customerRepositoryMock, $customerConfigMock);

        $customerTransfer = (new CustomerBuilder())
            ->build();
        $customerTransfer->setSalutation(null);

        $quoteTransfer = (new QuoteBuilder())
            ->withCustomer($customerTransfer->toArray())
            ->build();

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $result = $validator->validate($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($result);
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testValidateWithCaseSensitiveSalutation(): void
    {
        // Arrange
        $customerRepositoryMock = $this->createCustomerRepositoryMock();
        $customerRepositoryMock->method('getAllSalutations')
            ->willReturn(['Mr', 'Mrs', 'Ms']); // Uppercase

        $customerConfigMock = $this->createCustomerConfigMock();
        $customerConfigMock->method('getCustomerInvalidSalutationErrorCode')
            ->willReturn(static::ERROR_CODE);

        $validator = new CustomerCheckoutSalutationValidator($customerRepositoryMock, $customerConfigMock);

        $customerTransfer = (new CustomerBuilder([
            'salutation' => 'mr', // lowercase
        ]))
        ->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withCustomer($customerTransfer->toArray())
            ->build();

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $result = $validator->validate($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($result);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testValidateWithEmptySalutationsList(): void
    {
        // Arrange
        $customerRepositoryMock = $this->createCustomerRepositoryMock();
        $customerRepositoryMock->method('getAllSalutations')
            ->willReturn([]);

        $customerConfigMock = $this->createCustomerConfigMock();
        $customerConfigMock->method('getCustomerInvalidSalutationErrorCode')
            ->willReturn(static::ERROR_CODE);

        $validator = new CustomerCheckoutSalutationValidator($customerRepositoryMock, $customerConfigMock);

        $customerTransfer = (new CustomerBuilder([
            'salutation' => static::VALID_SALUTATION,
        ]))
        ->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withCustomer($customerTransfer->toArray())
            ->build();

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $result = $validator->validate($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($result);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface
     */
    protected function createCustomerRepositoryMock(): CustomerRepositoryInterface
    {
        return $this->getMockBuilder(CustomerRepositoryInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Customer\CustomerConfig
     */
    protected function createCustomerConfigMock(): CustomerConfig
    {
        return $this->getMockBuilder(CustomerConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
