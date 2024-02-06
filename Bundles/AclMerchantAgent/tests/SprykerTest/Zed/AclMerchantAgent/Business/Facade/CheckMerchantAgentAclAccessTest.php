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
 * @group CheckMerchantAgentAclAccessTest
 * Add your own group annotations below this line
 */
class CheckMerchantAgentAclAccessTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_BUNDLE_NAME = 'test-bundle';

    /**
     * @var \SprykerTest\Zed\AclMerchantAgent\AclMerchantAgentBusinessTester
     */
    protected AclMerchantAgentBusinessTester $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->mockAuthorizationChecker();
    }

    /**
     * @return void
     */
    public function testReturnsFalseWhenBundleIsNotListedInConfig(): void
    {
        // Arrange
        $userTransfer = new UserTransfer();
        $ruleTransfer = (new RuleTransfer())->setBundle(static::TEST_BUNDLE_NAME);

        // Act
        $isApplicable = $this->tester->getFacade()->checkMerchantAgentAclAccess(
            $userTransfer,
            $ruleTransfer,
        );

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testReturnsTrueWhenBundleIsListedInConfig(): void
    {
        // Arrange
        $this->tester->mockConfig([static::TEST_BUNDLE_NAME]);
        $userTransfer = new UserTransfer();
        $ruleTransfer = (new RuleTransfer())->setBundle(static::TEST_BUNDLE_NAME);

        // Act
        $isApplicable = $this->tester->getFacade()->checkMerchantAgentAclAccess(
            $userTransfer,
            $ruleTransfer,
        );

        // Assert
        $this->assertTrue($isApplicable);
    }
}
