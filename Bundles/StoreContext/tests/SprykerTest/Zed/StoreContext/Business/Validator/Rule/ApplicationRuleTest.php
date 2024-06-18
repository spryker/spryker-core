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
use Spryker\Zed\StoreContext\Business\Validator\Rule\ApplicationRule;
use Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface;
use Spryker\Zed\StoreContext\StoreContextConfig;
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
 * @group ApplicationRuleTest
 * Add your own group annotations below this line
 */
class ApplicationRuleTest extends Unit
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
        $rule = $this->createApplicationRule();
        // Act
        $errorTransfers = $rule->validateStoreContext($this->createStoreContextTransfer());

        // Assert
        $this->assertEmpty($errorTransfers);
    }

    /**
     * @return void
     */
    public function testValidateStoreContextReturnsErrorMessageApplicationNotValid(): void
    {
        // Arrange
        $rule = $this->createApplicationRule();

        $storeContextTransfer = $this->createStoreContextTransfer();
        $storeContextTransfer->getApplicationContextCollection()->getApplicationContexts()[0]->setApplication('wrong');

        // Act
        $errorMessages = $rule->validateStoreContext($storeContextTransfer);

        // Assert
        $this->assertCount(1, $errorMessages);
        $this->assertSame($this->tester::MESSAGE_APP_NOT_VALID, $errorMessages[0]->getMessage());
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface
     */
    protected function createApplicationRule(): StoreContextValidatorRuleInterface
    {
        $mockStoreContextConfig = $this->createMock(StoreContextConfig::class);
        $mockStoreContextConfig->method('getStoreContextApplications')->willReturn([$this->tester::APP_NAME, $this->tester::APP_NAME_YVES]);

        return new ApplicationRule($mockStoreContextConfig);
    }

    /**
     * @return \Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer
     */
    protected function createStoreApplicationContextCollectionTransfer(): StoreApplicationContextCollectionTransfer
    {
        return (new StoreApplicationContextCollectionTransfer())->addApplicationContext(
            (new StoreApplicationContextTransfer())->setTimezone($this->tester::TIMEZONE_DEFAULT),
        )->addApplicationContext(
            (new StoreApplicationContextTransfer())->setApplication($this->tester::APP_NAME)->setTimezone($this->tester::TIMEZONE_DEFAULT),
        )->addApplicationContext(
            (new StoreApplicationContextTransfer())->setApplication($this->tester::APP_NAME_YVES)->setTimezone($this->tester::TIMEZONE_ZED),
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
