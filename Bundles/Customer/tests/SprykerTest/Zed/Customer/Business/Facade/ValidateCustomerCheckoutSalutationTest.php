<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group ValidateCustomerCheckoutSalutationTest
 * Add your own group annotations below this line
 */
class ValidateCustomerCheckoutSalutationTest extends AbstractCustomerFacadeTest
{
    /**
     * @return void
     */
    public function testValidateCustomerCheckoutSalutationShouldReturnSuccessForValidSalutation(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())->setSalutation(static::TEST_SALUTATION),
            );
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);

        // Act
        $isValid = $this->tester->getCustomerFacade()
            ->validateCustomerCheckoutSalutation($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($isValid);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertEmpty($checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testValidateCustomerCheckoutSalutationShouldReturnSuccessForEmptySalutation(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setCustomer((new CustomerTransfer()));
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);

        // Act
        $isValid = $this->tester->getCustomerFacade()
            ->validateCustomerCheckoutSalutation($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($isValid);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertEmpty($checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testValidateCustomerCheckoutSalutationShouldReturnErrorForInvalidSalutation(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())->setSalutation(static::TEST_INVALID_SALUTATION),
            );
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);

        // Act
        $isValid = $this->tester->getCustomerFacade()
            ->validateCustomerCheckoutSalutation($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertNotEmpty($checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testValidateCustomerCheckoutSalutationShouldThrowAnExceptionWhenCustomerIsNotSet(): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getCustomerFacade()
            ->validateCustomerCheckoutSalutation($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * @dataProvider getCheckoutCustomerAddressData
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param bool $expectedIsValid
     *
     * @return void
     */
    public function testValidateCustomerAddressCheckoutSalutation(
        QuoteTransfer $quoteTransfer,
        bool $expectedIsValid
    ): void {
        // Arrange
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);

        // Act
        $isValid = $this->tester->getCustomerFacade()
            ->validateCustomerAddressCheckoutSalutation($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertSame($expectedIsValid, $isValid);
        $this->assertSame($expectedIsValid, $checkoutResponseTransfer->getIsSuccess());
        $this->assertSame($expectedIsValid, !$checkoutResponseTransfer->getErrors()->getArrayCopy());
    }

    /**
     * @return array
     */
    protected function getCheckoutCustomerAddressData(): array
    {
        return [
            [
                (new QuoteTransfer()), true,
            ],
            [
                (new QuoteTransfer())->setBillingAddress((new AddressTransfer())->setSalutation(static::TEST_SALUTATION)),
                true,
            ],
            [
                (new QuoteTransfer())->setShippingAddress((new AddressTransfer())->setSalutation(static::TEST_SALUTATION)),
                true,
            ],
            [
                (new QuoteTransfer())->addItem(
                    (new ItemTransfer())->setShipment(
                        (new ShipmentTransfer())->setShippingAddress(
                            (new AddressTransfer())->setSalutation(static::TEST_SALUTATION),
                        ),
                    ),
                ),
                true,
            ],
            [
                (new QuoteTransfer())
                    ->setBillingAddress((new AddressTransfer())->setSalutation(static::TEST_SALUTATION))
                    ->setShippingAddress((new AddressTransfer())->setSalutation(static::TEST_SALUTATION))
                    ->addItem(
                        (new ItemTransfer())->setShipment(
                            (new ShipmentTransfer())->setShippingAddress(
                                (new AddressTransfer())->setSalutation(static::TEST_SALUTATION),
                            ),
                        ),
                    ),
                true,
            ],
            [
                (new QuoteTransfer())
                    ->setBillingAddress((new AddressTransfer())->setSalutation(static::TEST_INVALID_SALUTATION))
                    ->setShippingAddress((new AddressTransfer())->setSalutation(static::TEST_SALUTATION))
                    ->addItem(
                        (new ItemTransfer())->setShipment(
                            (new ShipmentTransfer())->setShippingAddress(
                                (new AddressTransfer())->setSalutation(static::TEST_SALUTATION),
                            ),
                        ),
                    ),
                false,
            ],
            [
                (new QuoteTransfer())
                    ->setBillingAddress((new AddressTransfer())->setSalutation(static::TEST_SALUTATION))
                    ->setShippingAddress((new AddressTransfer())->setSalutation(static::TEST_INVALID_SALUTATION))
                    ->addItem(
                        (new ItemTransfer())->setShipment(
                            (new ShipmentTransfer())->setShippingAddress(
                                (new AddressTransfer())->setSalutation(static::TEST_SALUTATION),
                            ),
                        ),
                    ),
                false,
            ],
            [
                (new QuoteTransfer())
                    ->setBillingAddress((new AddressTransfer())->setSalutation(static::TEST_SALUTATION))
                    ->setShippingAddress((new AddressTransfer())->setSalutation(static::TEST_SALUTATION))
                    ->addItem(
                        (new ItemTransfer())->setShipment(
                            (new ShipmentTransfer())->setShippingAddress(
                                (new AddressTransfer())->setSalutation(static::TEST_INVALID_SALUTATION),
                            ),
                        ),
                    ),
                false,
            ],
            [
                (new QuoteTransfer())
                    ->setBillingAddress((new AddressTransfer())->setSalutation(static::TEST_SALUTATION))
                    ->setShippingAddress((new AddressTransfer())->setSalutation(static::TEST_SALUTATION))
                    ->addItem(
                        (new ItemTransfer())->setShipment(
                            (new ShipmentTransfer())->setShippingAddress(
                                (new AddressTransfer())->setSalutation(static::TEST_SALUTATION),
                            ),
                        ),
                    )
                    ->addItem(
                        (new ItemTransfer())->setShipment(
                            (new ShipmentTransfer())->setShippingAddress(
                                (new AddressTransfer())->setSalutation(static::TEST_INVALID_SALUTATION),
                            ),
                        ),
                    ),
                false,
            ],
        ];
    }
}
