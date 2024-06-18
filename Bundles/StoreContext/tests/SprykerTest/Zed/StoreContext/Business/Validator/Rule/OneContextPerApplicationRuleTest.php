<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreContext\Business\Validator\Rule;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer;
use Generated\Shared\Transfer\StoreApplicationContextTransfer;
use Generated\Shared\Transfer\StoreContextTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\StoreContext\Business\Validator\Rule\OneContextPerApplicationRule;
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
 * @group OneContextPerApplicationRuleTest
 * Add your own group annotations below this line
 */
class OneContextPerApplicationRuleTest extends Unit
{
    /**
     * @var string
     */
    protected const MESSAGE_APPLICATION_USED_MORE_THAN_ONCE = 'Application %application% is used more than once.';

    /**
     * @var \SprykerTest\Zed\StoreContext\StoreContextBusinessTester
     */
    protected StoreContextBusinessTester $tester;

    /**
     * @return void
     */
    public function testValidateStoreContextReturnsEmptyArray(): void
    {
        // Arrange
        $rule = $this->createOneContextPerApplicationRule();

        // Act
        $messages = $rule->validateStoreContext($this->createStoreContextTransfer());

        // Assert
        $this->assertEmpty($messages);
    }

    /**
     * @return void
     */
    public function testValidateStoreContextReturnsErrorMessageApplicationUsedMoreThenOnce(): void
    {
        // Arrange
        $rule = $this->createOneContextPerApplicationRule();
        $storeContextTransfer = $this->createStoreContextTransfer();
        $storeContextTransfer->getApplicationContextCollection()->addApplicationContext(
            (new StoreApplicationContextTransfer())->setApplication($this->tester::APP_NAME)->setTimezone($this->tester::TIMEZONE_DEFAULT),
        );

        // Act
        $messages = $rule->validateStoreContext($storeContextTransfer);

        // Assert
        $this->assertNotEmpty($messages);
        $this->assertInstanceOf(ErrorTransfer::class, $messages[0]);
        $this->assertSame(static::MESSAGE_APPLICATION_USED_MORE_THAN_ONCE, $messages[0]->getMessage());
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
        );
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface
     */
    protected function createOneContextPerApplicationRule(): StoreContextValidatorRuleInterface
    {
        $mockStoreContextConfig = $this->createMock(StoreContextConfig::class);
        $mockStoreContextConfig->method('getStoreContextApplications')->willReturn([$this->tester::APP_NAME, $this->tester::APP_NAME_YVES]);

        return new OneContextPerApplicationRule($mockStoreContextConfig);
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
