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
use Spryker\Zed\StoreContext\Business\Reader\TimezoneReader;
use Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface;
use Spryker\Zed\StoreContext\Business\Validator\Rule\TimezoneRule;
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
 * @group TimezoneRuleTest
 * Add your own group annotations below this line
 */
class TimezoneRuleTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\StoreContext\StoreContextBusinessTester
     */
    protected StoreContextBusinessTester $tester;

    /**
     * @return void
     */
    public function testValidateStoreContextReturnsEmptyMessages(): void
    {
        // Arrange
        $rule = $this->createTimezoneRule();
        $storeContextTransfer = (new StoreContextTransfer())->setApplicationContextCollection(
            (new StoreApplicationContextCollectionTransfer())->addApplicationContext(
                (new StoreApplicationContextTransfer())->setTimezone($this->tester::TIMEZONE_DEFAULT),
            ),
        )->setStore((new StoreTransfer())->setIdStore(999));

        // Act
        $messages = $rule->validateStoreContext($storeContextTransfer);

        // Assert
        $this->assertEmpty($messages);
    }

    /**
     * @return void
     */
    public function testValidateStoreContextReturnsErrorMessage(): void
    {
        // Arrange
        $rule = $this->createTimezoneRule();
        $storeContextTransfer = (new StoreContextTransfer())->setApplicationContextCollection(
            (new StoreApplicationContextCollectionTransfer())->addApplicationContext(
                (new StoreApplicationContextTransfer())->setTimezone('invalid-timezone'),
            ),
        )
            ->setStore((new StoreTransfer())->setIdStore(999));

        // Act
        $messages = $rule->validateStoreContext($storeContextTransfer);

        // Assert
        $this->assertNotEmpty($messages);
        $this->assertSame('Timezone %timezone% is not valid.', $messages[0]->getMessage());
    }

    /**
     * @return \Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface
     */
    protected function createTimezoneRule(): StoreContextValidatorRuleInterface
    {
        return new TimezoneRule(
            new TimezoneReader(),
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
