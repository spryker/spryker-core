<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Constraint;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProduct\Business\Constraint\ValidCurrencyAssignedToStoreConstraintValidator;
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
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->validCurrencyAssignedToStoreConstraint = $this->tester->getFacade()->getValidCurrencyAssignedToStoreConstraint();
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
        $priceProductTransfer = new PriceProductTransfer();
        $moneyValueTransfer = new MoneyValueTransfer();
        $currencyTransfer = $this->tester->haveCurrencyTransfer();
        $priceProductTransfer->setMoneyValue($moneyValueTransfer);
        $moneyValueTransfer->setCurrency($currencyTransfer);
        $moneyValueTransfer->setFkStore(1);
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
        $priceProductTransfer = new PriceProductTransfer();
        $moneyValueTransfer = new MoneyValueTransfer();
        $currencyTransfer = $this->tester->haveCurrencyTransfer();
        $currencyTransfer->setCode(static::FAKE_CURRENCY);
        $priceProductTransfer->setMoneyValue($moneyValueTransfer);
        $moneyValueTransfer->setCurrency($currencyTransfer);
        $moneyValueTransfer->setFkStore(2);
        $this->executionContextMock->expects($this->once())->method('addViolation');

        // Act
        $this->validCurrencyAssignedToStoreConstraintValidator->validate($priceProductTransfer, $this->validCurrencyAssignedToStoreConstraint);
    }
}
