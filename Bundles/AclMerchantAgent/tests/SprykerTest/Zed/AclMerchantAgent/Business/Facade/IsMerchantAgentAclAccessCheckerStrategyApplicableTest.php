<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AclMerchantAgent\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RuleTransfer;
use Generated\Shared\Transfer\UserTransfer;
use SprykerTest\Zed\AclMerchantAgent\AclMerchantAgentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AclMerchantAgent
 * @group Business
 * @group Facade
 * @group IsMerchantAgentAclAccessCheckerStrategyApplicableTest
 * Add your own group annotations below this line
 */
class IsMerchantAgentAclAccessCheckerStrategyApplicableTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\AclMerchantAgent\AclMerchantAgentBusinessTester
     */
    protected AclMerchantAgentBusinessTester $tester;

    /**
     * @return void
     */
    public function testReturnsTrue(): void
    {
        // Arrange
        $this->tester->mockAuthorizationChecker();
        $userTransfer = (new UserTransfer())->setIsMerchantAgent(true);
        $ruleTransfer = new RuleTransfer();

        // Act
        $isApplicable = $this->tester->getFacade()->isMerchantAgentAclAccessCheckerStrategyApplicable(
            $userTransfer,
            $ruleTransfer,
        );

        // Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testReturnsFalseWhenUserIsNotMerchantAgent(): void
    {
        // Arrange
        $this->tester->mockAuthorizationChecker();
        $userTransfer = new UserTransfer();
        $ruleTransfer = new RuleTransfer();

        // Act
        $isApplicable = $this->tester->getFacade()->isMerchantAgentAclAccessCheckerStrategyApplicable(
            $userTransfer,
            $ruleTransfer,
        );

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testReturnsFalseWhenMerchantAgentAccessIsNotGranted(): void
    {
        // Arrange
        $this->tester->mockAuthorizationChecker(false);
        $userTransfer = (new UserTransfer())->setIsMerchantAgent(true);
        $ruleTransfer = new RuleTransfer();

        // Act
        $isApplicable = $this->tester->getFacade()->isMerchantAgentAclAccessCheckerStrategyApplicable(
            $userTransfer,
            $ruleTransfer,
        );

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testReturnsFalseWhenExceptionIsThrown(): void
    {
        // Arrange
        $this->tester->mockAuthorizationChecker(true, true);
        $userTransfer = (new UserTransfer())->setIsMerchantAgent(true);
        $ruleTransfer = new RuleTransfer();

        // Act
        $isApplicable = $this->tester->getFacade()->isMerchantAgentAclAccessCheckerStrategyApplicable(
            $userTransfer,
            $ruleTransfer,
        );

        // Assert
        $this->assertFalse($isApplicable);
    }
}
