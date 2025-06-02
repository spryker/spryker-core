<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\MultiFactorAuth\Communication\Plugin\Factors\Email;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use ReflectionClass;
use Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacade;
use Spryker\Zed\MultiFactorAuth\Communication\Plugin\Factors\Email\UserEmailMultiFactorAuthPlugin;
use SprykerTest\Zed\MultiFactorAuth\MultiFactorAuthCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MultiFactorAuth
 * @group Communication
 * @group Plugin
 * @group Factors
 * @group Email
 * @group UserEmailMultiFactorAuthPluginTest
 * Add your own group annotations below this line
 */
class UserEmailMultiFactorAuthPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const EMAIL_MULTI_FACTOR_AUTH_METHOD = 'email';

    /**
     * @var \SprykerTest\Zed\MultiFactorAuth\MultiFactorAuthCommunicationTester
     */
    protected MultiFactorAuthCommunicationTester $tester;

    /**
     * @var \Spryker\Zed\MultiFactorAuth\Communication\Plugin\Factors\Email\UserEmailMultiFactorAuthPlugin
     */
    protected UserEmailMultiFactorAuthPlugin $userEmailMultiFactorAuthPlugin;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userEmailMultiFactorAuthPlugin = new UserEmailMultiFactorAuthPlugin();
    }

    /**
     * @return void
     */
    public function testGetNameReturnsCorrectValue(): void
    {
        // Act
        $name = $this->userEmailMultiFactorAuthPlugin->getName();

        // Assert
        $this->assertSame(static::EMAIL_MULTI_FACTOR_AUTH_METHOD, $name);
    }

    /**
     * @return void
     */
    public function testIsApplicableReturnsTrueForEmailMethod(): void
    {
        // Act
        $isApplicable = $this->userEmailMultiFactorAuthPlugin->isApplicable(static::EMAIL_MULTI_FACTOR_AUTH_METHOD);

        // Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsApplicableReturnsFalseForNonEmailMethod(): void
    {
        // Act
        $isApplicable = $this->userEmailMultiFactorAuthPlugin->isApplicable('non-email');

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testGetConfigurationReturnsEmptyString(): void
    {
        // Arrange
        $multiFactorAuthTransfer = new MultiFactorAuthTransfer();

        // Act
        $configuration = $this->userEmailMultiFactorAuthPlugin->getConfiguration($multiFactorAuthTransfer);

        // Assert
        $this->assertSame('', $configuration);
    }

    /**
     * @return void
     */
    public function testSendCodeCallsFacadeMethod(): void
    {
        // Arrange
        $multiFactorAuthTransfer = $this->tester->createMultiFactorAuthTransfer($this->tester::TYPE_EMAIL);

        $facadeMock = $this->getMockBuilder(MultiFactorAuthFacade::class)
            ->disableOriginalConstructor()
            ->getMock();

        $facadeMock->expects($this->once())
            ->method('sendUserCode')
            ->with($this->equalTo($multiFactorAuthTransfer));

        $userEmailMultiFactorAuthPluginReflection = new ReflectionClass(UserEmailMultiFactorAuthPlugin::class);

        $facadeProperty = $userEmailMultiFactorAuthPluginReflection->getProperty('facade');
        $facadeProperty->setValue($this->userEmailMultiFactorAuthPlugin, $facadeMock);

        // Act
        $this->userEmailMultiFactorAuthPlugin->sendCode($multiFactorAuthTransfer);
    }
}
