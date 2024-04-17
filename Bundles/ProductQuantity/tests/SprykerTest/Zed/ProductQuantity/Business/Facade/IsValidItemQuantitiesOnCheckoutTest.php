<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductQuantity\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use SprykerTest\Zed\ProductQuantity\ProductQuantityBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductQuantity
 * @group Business
 * @group Facade
 * @group IsValidItemQuantitiesOnCheckoutTest
 * Add your own group annotations below this line
 */
class IsValidItemQuantitiesOnCheckoutTest extends Unit
{
    /**
     * @uses \Spryker\Zed\ProductQuantity\Business\Model\Validator\ProductQuantityRestrictionValidator::ERROR_QUANTITY_MIN_NOT_FULFILLED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_QUANTITY_MIN_NOT_FULFILLED = 'cart.pre.check.quantity.min.failed';

    /**
     * @uses \Spryker\Zed\ProductQuantity\Business\Model\Validator\ProductQuantityRestrictionValidator::ERROR_QUANTITY_MAX_NOT_FULFILLED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_QUANTITY_MAX_NOT_FULFILLED = 'cart.pre.check.quantity.max.failed';

    /**
     * @uses \Spryker\Zed\ProductQuantity\Business\Model\Validator\ProductQuantityRestrictionValidator::ERROR_QUANTITY_INTERVAL_NOT_FULFILLED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_QUANTITY_INTERVAL_NOT_FULFILLED = 'cart.pre.check.quantity.interval.failed';

    /**
     * @uses \Spryker\Zed\ProductQuantity\Business\Model\Validator\ProductQuantityRestrictionValidator::ERROR_QUANTITY_INCORRECT
     *
     * @var string
     */
    protected const GLOSSARY_KEY_QUANTITY_INCORRECT = 'cart.pre.check.quantity.value.failed';

    /**
     * @var \SprykerTest\Zed\ProductQuantity\ProductQuantityBusinessTester
     */
    protected ProductQuantityBusinessTester $tester;

    /**
     * @return void
     */
    public function testReturnsNoErrorsWhenProductDoesNotHaveRestrictions(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => $productTransfer->getSku()])
            ->build();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $result = $this->tester->getFacade()->isValidItemQuantitiesOnCheckout($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($result);
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @dataProvider returnsNoErrorsWhenProductQuantityRestrictionsAreFulfilledDataProvider
     *
     * @param int $itemQuantity
     * @param int $minRestriction
     * @param int $intervalRestriction
     * @param int|null $maxRestriction
     *
     * @return void
     */
    public function testReturnsNoErrorsWhenProductQuantityRestrictionsAreFulfilled(
        int $itemQuantity,
        int $minRestriction,
        int $intervalRestriction,
        ?int $maxRestriction
    ): void {
        // Arrange
        $productTransfer = $this->tester->createProductWithSpecificProductQuantity(
            $minRestriction,
            $maxRestriction,
            $intervalRestriction,
        );
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => $productTransfer->getSku(),
                ItemTransfer::QUANTITY => $itemQuantity,
            ])
            ->build();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $result = $this->tester->getFacade()->isValidItemQuantitiesOnCheckout($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($result);
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @dataProvider returnsExpectedErrorWhenItemQuantityDoesNotFulfillProductQuantityRestrictionsDataProvider
     *
     * @param string $errorMessage
     * @param string|float|int $itemQuantity
     * @param int $minRestriction
     * @param int $intervalRestriction
     * @param int|null $maxRestriction
     *
     * @return void
     */
    public function testReturnsExpectedErrorWhenItemQuantityDoesNotFulfillProductQuantityRestrictions(
        string $errorMessage,
        string|float|int $itemQuantity,
        int $minRestriction,
        int $intervalRestriction,
        ?int $maxRestriction
    ): void {
        // Arrange
        $productTransfer = $this->tester->createProductWithSpecificProductQuantity(
            $minRestriction,
            $maxRestriction,
            $intervalRestriction,
        );
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => $productTransfer->getSku(),
                ItemTransfer::QUANTITY => $itemQuantity,
            ])
            ->build();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $result = $this->tester->getFacade()->isValidItemQuantitiesOnCheckout($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($result);
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->assertSame($errorMessage, $checkoutResponseTransfer->getErrors()->getIterator()->current()->getMessage());
    }

    /**
     * @return array<string, list<int|null>>
     */
    protected function returnsNoErrorsWhenProductQuantityRestrictionsAreFulfilledDataProvider(): array
    {
        return [
            'Item quantity is greater than min quantity restriction' => [2, 1, 1, null],
            'Item quantity is equal to min quantity restriction' => [1, 1, 1, null],
            'Item quantity matches product quantity interval restriction' => [4, 2, 2, null],
            'Item quantity is less than max quantity restriction' => [2, 1, 1, 3],
            'Item quantity is equal to max quantity restriction' => [3, 1, 1, 3],
        ];
    }

    /**
     * @return array<string, list<string|int|float|null>>
     */
    protected function returnsExpectedErrorWhenItemQuantityDoesNotFulfillProductQuantityRestrictionsDataProvider(): array
    {
        return [
            'item quantity is less than min product quantity' => [static::GLOSSARY_KEY_QUANTITY_MIN_NOT_FULFILLED, 1, 2, 1, null],
            'item quantity is bigger than max product quantity' => [static::GLOSSARY_KEY_QUANTITY_MAX_NOT_FULFILLED, 3, 1, 1, 2],
            'item quantity does not match product quantity interval' => [static::GLOSSARY_KEY_QUANTITY_INTERVAL_NOT_FULFILLED, 5, 1, 3, null],
            'item quantity is not a positive int (string)' => [static::GLOSSARY_KEY_QUANTITY_INCORRECT, 'NaN', 1, 1, null],
            'item quantity is not a positive int (float)' => [static::GLOSSARY_KEY_QUANTITY_INCORRECT, 1.5, 1, 1, null],
            'item quantity is not a positive int (negative int)' => [static::GLOSSARY_KEY_QUANTITY_INCORRECT, -1, 1, 1, null],
        ];
    }
}
