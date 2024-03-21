<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Customer\Business\Model\PreConditionChecker;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group CheckOrderPreSaveConditionsTest
 * Add your own group annotations below this line
 */
class CheckOrderPreSaveConditionsTest extends AbstractCustomerFacadeTest
{
    /**
     * @var string
     */
    protected const TESTER_EMAIL = 'tester@spryker.com';

    /**
     * @var string
     */
    protected const TESTER_INVALID_EMAIL = 'tester<>@spryker.com';

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsDoesNotValidateEmailForRegisteredCustomer(): void
    {
        // Arrange
        $dummyIdCustomer = 11111;
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setIdCustomer($dummyIdCustomer)
                    ->setEmail(static::TESTER_INVALID_EMAIL),
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->tester->getCustomerFacade()->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_INVALID));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsDoesNotCheckUniqueEmailForRegisteredCustomer(): void
    {
        // Arrange
        $dummyCustomerId = 11111;
        $email = 'occupied@spryker.com';
        $this->tester->haveCustomer(['email' => $email, 'password' => static::VALUE_VALID_PASSWORD]);

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setIdCustomer($dummyCustomerId)
                    ->setEmail($email),
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->tester->getCustomerFacade()->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_UNIQUE));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsReturnsErrorIfEmailIsInvalidForGuest(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setIsGuest(true)
                    ->setEmail(static::TESTER_INVALID_EMAIL),
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->tester->getCustomerFacade()->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_INVALID));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsReturnsNoErrorIfEmailIsValidForGuest(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setIsGuest(true)
                    ->setEmail(static::TESTER_EMAIL),
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->tester->getCustomerFacade()->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_INVALID));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsDoesNotCheckUniqueEmailForGuest(): void
    {
        // Arrange
        $email = 'occupied@spryker.com';
        $this->tester->haveCustomer(['email' => $email, 'password' => static::VALUE_VALID_PASSWORD]);

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setIsGuest(true)
                    ->setEmail($email),
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->tester->getCustomerFacade()->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_UNIQUE));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsReturnsErrorIfEmailIsInvalidForNewCustomer(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setEmail(static::TESTER_INVALID_EMAIL),
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->tester->getCustomerFacade()->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_INVALID));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsReturnsErrorIfEmailIsNotUniqueForNewCustomer(): void
    {
        // Arrange
        $email = 'occupied@spryker.com';
        $this->tester->haveCustomer(['email' => $email, 'password' => static::VALUE_VALID_PASSWORD]);

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setEmail($email),
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->tester->getCustomerFacade()->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_UNIQUE));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsReturnsNoErrorIfEmailIsValidAndUniqueForNewCustomer(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setEmail(static::TESTER_EMAIL),
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->tester->getCustomerFacade()->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_UNIQUE));
        $this->assertFalse($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_INVALID));
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param string $errorMessage
     *
     * @return bool
     */
    protected function hasCheckoutErrorMessage(CheckoutResponseTransfer $checkoutResponseTransfer, string $errorMessage): bool
    {
        foreach ($checkoutResponseTransfer->getErrors() as $errorTransfer) {
            if ($errorTransfer->getMessage() === $errorMessage) {
                return true;
            }
        }

        return false;
    }
}
