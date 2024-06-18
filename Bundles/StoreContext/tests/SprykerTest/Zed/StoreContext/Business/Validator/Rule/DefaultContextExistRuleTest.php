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
use Spryker\Zed\StoreContext\Business\Validator\Rule\DefaultContextExistRule;
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
 * @group DefaultContextExistRuleTest
 * Add your own group annotations below this line
 */
class DefaultContextExistRuleTest extends Unit
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
        $rule = $this->createDefaultConfigurationExistRule();

        // Act
        $messages = $rule->validateStoreContext($this->createStoreContextTransfer());

        // Assert
        $this->assertEmpty($messages);
    }

    /**
     * @return void
     */
    public function testValidateStoreContextnReturnsErrorMessageDefaultContextNotExist(): void
    {
        // Arrange
        $rule = $this->createDefaultConfigurationExistRule();

        $storeContextTransfer = $this->createStoreContextTransfer();
        $storeContextTransfer->getApplicationContextCollection()->getApplicationContexts()[0]->setApplication($this->tester::APP_NAME_YVES);

        // Act
        $messages = $rule->validateStoreContext($storeContextTransfer);

        // Assert
        $this->assertCount(1, $messages);
        $this->assertSame($this->tester::MESSAGE_DEFAULT_CONTEXT_NOT_EXIST, $messages[0]->getMessage());
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface
     */
    protected function createDefaultConfigurationExistRule(): StoreContextValidatorRuleInterface
    {
        return new DefaultContextExistRule();
    }

    /**
     * @return \Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer
     */
    protected function createStoreApplicationContextCollectionTransfer(): StoreApplicationContextCollectionTransfer
    {
        return (new StoreApplicationContextCollectionTransfer())->addApplicationContext(
            (new StoreApplicationContextTransfer())->setTimezone($this->tester::TIMEZONE_DEFAULT),
        );
    }

    /**
     * @return \Generated\Shared\Transfer\StoreContextTransfer
     */
    protected function createStoreContextTransfer(): StoreContextTransfer
    {
        return (new StoreContextTransfer())->setApplicationContextCollection(
            $this->createStoreApplicationContextCollectionTransfer(),
        )->setStore((new StoreTransfer())->setIdStore(999));
    }
}
