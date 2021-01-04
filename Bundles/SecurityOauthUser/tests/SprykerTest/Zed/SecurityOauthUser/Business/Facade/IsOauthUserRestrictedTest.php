<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityOauthUser\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\OauthUserRestrictionRequestBuilder;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OauthUserRestrictionRequestTransfer;
use Generated\Shared\Transfer\OauthUserRestrictionResponseTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserRestrictionPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityOauthUser
 * @group Business
 * @group Facade
 * @group IsOauthUserRestrictedTest
 * Add your own group annotations below this line
 */
class IsOauthUserRestrictedTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SecurityOauthUser\SecurityOauthUserBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsOauthUserRestrictedShouldRestrictOauthUser(): void
    {
        // Arrange
        $this->tester->setOauthUserRestrictionPlugin($this->createOauthUserRestrictionPluginMock(true));

        $oauthUserRestrictionRequestTransfer = (new OauthUserRestrictionRequestBuilder())
            ->withUser()
            ->build();

        // Act
        $oauthUserRestrictionResponseTransfer = $this->tester
            ->getSecurityOauthUserFacade()
            ->isOauthUserRestricted($oauthUserRestrictionRequestTransfer);

        //Assert
        $this->assertTrue(
            $oauthUserRestrictionResponseTransfer->getIsRestricted(),
            'Expected that `IsRestricted` flag equals to true.'
        );
        $this->assertGreaterThan(
            0,
            $oauthUserRestrictionResponseTransfer->getMessages()->count(),
            'Expected to receive an error message.'
        );
    }

    /**
     * @return void
     */
    public function testIsOauthUserRestrictedShouldNotRestrictOauthUser(): void
    {
        // Arrange
        $this->tester->setOauthUserRestrictionPlugin($this->createOauthUserRestrictionPluginMock(false));

        $oauthUserRestrictionRequestTransfer = (new OauthUserRestrictionRequestBuilder())
            ->withUser()
            ->build();

        // Act
        $oauthUserRestrictionResponseTransfer = $this->tester
            ->getSecurityOauthUserFacade()
            ->isOauthUserRestricted($oauthUserRestrictionRequestTransfer);

        //Assert
        $this->assertFalse(
            $oauthUserRestrictionResponseTransfer->getIsRestricted(),
            'Expected that `IsRestricted` flag equals to false.'
        );
        $this->assertSame(
            0,
            $oauthUserRestrictionResponseTransfer->getMessages()->count(),
            'Expected to not receive any error messages.'
        );
    }

    /**
     * @return void
     */
    public function testIsOauthUserRestrictedThrowAnExceptionWhenRequiredDataIsNotProvided(): void
    {
        // Arrange
        $oauthUserRestrictionRequestTransfer = new OauthUserRestrictionRequestTransfer();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getSecurityOauthUserFacade()->isOauthUserRestricted($oauthUserRestrictionRequestTransfer);
    }

    /**
     * @param bool $isOauthUserRestricted
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserRestrictionPluginInterface
     */
    protected function createOauthUserRestrictionPluginMock(
        bool $isOauthUserRestricted
    ): OauthUserRestrictionPluginInterface {
        $oauthUserRestrictionPluginMock = $this->getMockBuilder(OauthUserRestrictionPluginInterface::class)
            ->getMock();

        $oauthUserRestrictionResponseTransfer = (new OauthUserRestrictionResponseTransfer())
            ->setIsRestricted($isOauthUserRestricted);

        if ($isOauthUserRestricted) {
            $oauthUserRestrictionResponseTransfer->addMessage(new MessageTransfer());
        }

        $oauthUserRestrictionPluginMock
            ->method('isRestricted')
            ->willReturn($oauthUserRestrictionResponseTransfer);

        return $oauthUserRestrictionPluginMock;
    }
}
