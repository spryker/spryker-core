<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreContext\Business\Validator\Rule;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer;
use Generated\Shared\Transfer\StoreApplicationContextTransfer;
use Generated\Shared\Transfer\StoreContextTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\StoreContext\Business\Reader\StoreContextReaderInterface;
use Spryker\Zed\StoreContext\Business\Validator\Rule\ContextNotFoundRule;
use Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface;
use SprykerTest\Zed\StoreContext\StoreContextBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StoreContext
 * @group Business
 * @group Validator
 * @group Rule
 * @group ContextNotFoundRuleTest
 * Add your own group annotations below this line
 */
class ContextNotFoundRuleTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\StoreContext\StoreContextBusinessTester
     */
    protected StoreContextBusinessTester $tester;

    /**
     * @return void
     */
    public function testValidateStoreContextReturnsEmptyErrorTransfers(): void
    {
        // Arrange
        $readerMock = $this->createMock(StoreContextReaderInterface::class);
        $readerMock->method('getStoreApplicationContextCollectionByIdStore')
            ->willReturn((new StoreApplicationContextCollectionTransfer())->addApplicationContext(
                new StoreApplicationContextTransfer(),
            ));

        $rule = new ContextNotFoundRule($readerMock);

        // Act
        $errorTransfers = $rule->validateStoreContext($this->createStoreContextTransfer());

        // Assert
        $this->assertEmpty($errorTransfers);
    }

    /**
     * @return void
     */
    public function testValidateStoreContextReturnsErrorMessageStoreContextNotFound(): void
    {
        // Arrange
        $readerMock = $this->createMock(StoreContextReaderInterface::class);
        $readerMock->method('getStoreApplicationContextCollectionByIdStore')
            ->willReturn((new StoreApplicationContextCollectionTransfer()));

        $rule = new ContextNotFoundRule($readerMock);

        // Act
        $errorMessages = $rule->validateStoreContext($this->createStoreContextTransfer());

        // Assert
        $this->assertCount(1, $errorMessages);
        $this->assertSame('Store context not found for store id: %id%.', $errorMessages[0]->getMessage());
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface
     */
    protected function createApplicationRule(): StoreContextValidatorRuleInterface
    {
        $readerMock = $this->createMock(StoreContextReaderInterface::class);
        $readerMock->method('getStoreApplicationContextCollectionByIdStore')
            ->willReturn((new StoreApplicationContextCollectionTransfer())->addApplicationContext(
                new StoreApplicationContextTransfer(),
            ));

        return new ContextNotFoundRule($readerMock);
    }

    /**
     * @return \Generated\Shared\Transfer\StoreContextTransfer
     */
    protected function createStoreContextTransfer(): StoreContextTransfer
    {
        return (new StoreContextTransfer())->setStore((new StoreTransfer())->setIdStore(999));
    }
}
