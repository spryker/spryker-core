<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Constraint;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\PriceProduct\Business\Constraint\ValidCurrencyAssignedToStoreConstraint;
use Spryker\Zed\PriceProduct\Business\Constraint\ValidCurrencyAssignedToStoreConstraintValidator;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Constraint
 * @group ValidCurrencyAssignedToStoreConstraintValidatorTest
 * Add your own group annotations below this line
 */
class ValidCurrencyAssignedToStoreConstraintValidatorTest extends Unit
{
    protected const FAKE_CURRENCY = 'RUB';

    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected $tester;

    /**
     * @var \Symfony\Component\Validator\Constraint
     */
    protected $validCurrencyAssignedToStoreConstraint;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Constraint\ValidCurrencyAssignedToStoreConstraintValidator
     */
    protected $validCurrencyAssignedToStoreConstraintValidator;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject & ExecutionContextInterface
     */
    protected $executionContextMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject & PriceProductToStoreFacadeInterface
     */
    protected $storeFacadeMock;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->storeFacadeMock = $this->getMockForAbstractClass(PriceProductToStoreFacadeInterface::class);
        $this->validCurrencyAssignedToStoreConstraint = new ValidCurrencyAssignedToStoreConstraint($this->storeFacadeMock);
        $this->validCurrencyAssignedToStoreConstraintValidator = new ValidCurrencyAssignedToStoreConstraintValidator();
        $this->executionContextMock = $this->getMockForAbstractClass(ExecutionContextInterface::class);
        $this->validCurrencyAssignedToStoreConstraintValidator->initialize($this->executionContextMock);
    }

    /**
     * @return void
     */
    public function testValidateSuccess()
    {
        // Assign
        $currencyTransfer = $this->tester->haveCurrencyTransfer();
        $storeTransfer = (new StoreTransfer())->addAvailableCurrencyIsoCode($currencyTransfer->getCode());
        $this->storeFacadeMock->method('getStoreById')->willReturn($storeTransfer);
        $moneyValueTransfer = (new MoneyValueTransfer())
            ->setCurrency($currencyTransfer)
            ->setFkStore(1);
        $priceProductTransfer = (new PriceProductTransfer())
            ->setMoneyValue($moneyValueTransfer);
        $this->executionContextMock->expects($this->never())->method('addViolation');

        // Act
        $this->validCurrencyAssignedToStoreConstraintValidator->validate($priceProductTransfer, $this->validCurrencyAssignedToStoreConstraint);
    }

    /**
     * @return void
     */
    public function testValidateFails()
    {
        // Assign
        $currencyTransfer = $this->tester->haveCurrencyTransfer();
        $storeTransfer = (new StoreTransfer())->addAvailableCurrencyIsoCode(static::FAKE_CURRENCY);
        $this->storeFacadeMock->method('getStoreById')->willReturn($storeTransfer);
        $moneyValueTransfer = (new MoneyValueTransfer())
            ->setCurrency($currencyTransfer);
        $priceProductTransfer = (new PriceProductTransfer())
            ->setMoneyValue($moneyValueTransfer);
        $this->executionContextMock->expects($this->once())->method('addViolation');

        // Act
        $this->validCurrencyAssignedToStoreConstraintValidator->validate($priceProductTransfer, $this->validCurrencyAssignedToStoreConstraint);
    }
}
