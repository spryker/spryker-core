<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AclMerchantPortal\Business\Generator;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MerchantBuilder;
use Generated\Shared\DataBuilder\MerchantUserBuilder;
use Generated\Shared\DataBuilder\UserBuilder;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\AclMerchantPortal\Business\Generator\AclMerchantPortalGenerator;
use SprykerTest\Zed\AclMerchantPortal\AclMerchantPortalBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AclMerchantPortal
 * @group Business
 * @group Generator
 * @group AclMerchantPortalGeneratorTest
 * Add your own group annotations below this line
 */
class AclMerchantPortalGeneratorTest extends Unit
{
    /**
     * @var string
     */
    protected const NAME_WITH_FULL_NAME = 'AnyMerchantName - MerchantPortral - AnyFirstname AnyLastname';

    /**
     * @var string
     */
    protected const NAME_WITH_USERNAME = 'AnyMerchantName - MerchantPortral - AnyUsername';

    /**
     * @var \SprykerTest\Zed\AclMerchantPortal\AclMerchantPortalBusinessTester
     */
    protected AclMerchantPortalBusinessTester $tester;

    /**
     * @return void
     */
    public function testGenerateAclMerchantUserGroupNameShouldReturnNameIncludingUsersFullName(): void
    {
        // Arrange
        $aclMerchantPortalConfig = $this->tester->getModuleConfig();
        $aclMerchantPortalGenerator = new AclMerchantPortalGenerator($aclMerchantPortalConfig);
        $merchantUserTransfer = $this->createMerchantUserTransfer();

        // Act
        $aclMerchantUserGroupName = $aclMerchantPortalGenerator->generateAclMerchantUserGroupName($merchantUserTransfer);

        // Assert
        $this->assertSame(static::NAME_WITH_FULL_NAME, $aclMerchantUserGroupName);
    }

    /**
     * @return void
     */
    public function testGenerateAclMerchantUserGroupNameShouldReturnNameIncludingUsersUsername(): void
    {
        // Arrange
        $aclMerchantPortalConfig = $this->tester->mockConfigMethod('isMerchantToMerchantUserConjunctionByUsernameEnabled', true);
        $aclMerchantPortalGenerator = new AclMerchantPortalGenerator($aclMerchantPortalConfig);
        $merchantUserTransfer = $this->createMerchantUserTransfer();

        // Act
        $aclMerchantUserGroupName = $aclMerchantPortalGenerator->generateAclMerchantUserGroupName($merchantUserTransfer);

        // Assert
        $this->assertSame(static::NAME_WITH_USERNAME, $aclMerchantUserGroupName);
    }

    /**
     * @return void
     */
    public function testGenerateAclMerchantUserRoleNameShouldReturnNameIncludingUsersFullName(): void
    {
        // Arrange
        $aclMerchantPortalConfig = $this->tester->getModuleConfig();
        $aclMerchantPortalGenerator = new AclMerchantPortalGenerator($aclMerchantPortalConfig);
        $merchantUserTransfer = $this->createMerchantUserTransfer();

        // Act
        $aclMerchantUserRoleName = $aclMerchantPortalGenerator->generateAclMerchantUserRoleName($merchantUserTransfer);

        // Assert
        $this->assertSame(static::NAME_WITH_FULL_NAME, $aclMerchantUserRoleName);
    }

    /**
     * @return void
     */
    public function testGenerateAclMerchantUserRoleNameShouldReturnNameIncludingUsersUsername(): void
    {
        // Arrange
        $aclMerchantPortalConfig = $this->tester->mockConfigMethod('isMerchantToMerchantUserConjunctionByUsernameEnabled', true);
        $aclMerchantPortalGenerator = new AclMerchantPortalGenerator($aclMerchantPortalConfig);
        $merchantUserTransfer = $this->createMerchantUserTransfer();

        // Act
        $aclMerchantUserRoleName = $aclMerchantPortalGenerator->generateAclMerchantUserRoleName($merchantUserTransfer);

        // Assert
        $this->assertSame(static::NAME_WITH_USERNAME, $aclMerchantUserRoleName);
    }

    /**
     * @return void
     */
    public function testGenerateAclMerchantUserSegmentNameShouldReturnNameIncludingUsersFullName(): void
    {
        // Arrange
        $aclMerchantPortalConfig = $this->tester->getModuleConfig();
        $aclMerchantPortalGenerator = new AclMerchantPortalGenerator($aclMerchantPortalConfig);
        $merchantUserTransfer = $this->createMerchantUserTransfer();

        // Act
        $aclMerchantUserSegmentName = $aclMerchantPortalGenerator->generateAclMerchantUserSegmentName(
            $merchantUserTransfer->getMerchantOrFail(),
            $merchantUserTransfer->getUserOrFail(),
        );

        // Assert
        $this->assertSame(static::NAME_WITH_FULL_NAME, $aclMerchantUserSegmentName);
    }

    /**
     * @return void
     */
    public function testGenerateAclMerchantUserSegmentNameShouldReturnNameIncludingUsersUsername(): void
    {
        // Arrange
        $aclMerchantPortalConfig = $this->tester->mockConfigMethod('isMerchantToMerchantUserConjunctionByUsernameEnabled', true);
        $aclMerchantPortalGenerator = new AclMerchantPortalGenerator($aclMerchantPortalConfig);
        $merchantUserTransfer = $this->createMerchantUserTransfer();

        // Act
        $aclMerchantUserSegmentName = $aclMerchantPortalGenerator->generateAclMerchantUserSegmentName(
            $merchantUserTransfer->getMerchantOrFail(),
            $merchantUserTransfer->getUserOrFail(),
        );

        // Assert
        $this->assertSame(static::NAME_WITH_USERNAME, $aclMerchantUserSegmentName);
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    protected function createMerchantUserTransfer(): MerchantUserTransfer
    {
        return (new MerchantUserBuilder([
            MerchantUserTransfer::MERCHANT => $this->createMerchantTransfer(),
            MerchantUserTransfer::USER => $this->createUserTransfer(),
        ]))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function createMerchantTransfer(): MerchantTransfer
    {
        return $merchantTransfer = (new MerchantBuilder([
            MerchantTransfer::NAME => 'AnyMerchantName',
        ]))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function createUserTransfer(): UserTransfer
    {
        return (new UserBuilder([
            UserTransfer::FIRST_NAME => 'AnyFirstname',
            UserTransfer::LAST_NAME => 'AnyLastname',
            UserTransfer::USERNAME => 'AnyUsername',
        ]))->build();
    }
}
