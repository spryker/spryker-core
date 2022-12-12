<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthCodeFlow\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer;
use Spryker\Zed\OauthCodeFlow\Business\Builders\UserAuthCodeGrantTypeBuilder;
use Spryker\Zed\OauthCodeFlow\Communication\Plugin\Oauth\UserAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRequestGrantTypeConfigurationProviderPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthCodeFlow
 * @group Communication
 * @group Plugin
 * @group UserAuthCodeOauthRequestGrantTypeConfigurationProviderPluginTest
 * Add your own group annotations below this line
 */
class UserAuthCodeOauthRequestGrantTypeConfigurationProviderPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\OauthCodeFlow\Communication\Plugin\Oauth\UserAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin::GLUE_BACKEND_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION = 'GLUE_BACKEND_API_APPLICATION';

    /**
     * @var \SprykerTest\Zed\OauthCodeFlow\OauthCodeFlowCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUserAuthCodeOauthRequestGrantTypeConfigurationProviderPluginIsApplicable(): void
    {
        //Arrange
        $oauthRequestTransfer = $this->tester
            ->createOauthRequestTransfer()
            ->setGrantType($this->tester::GRANT_TYPE_AUTHORIZATION_CODE);
        $glueAuthenticationRequestContextTransfer = $this->tester
            ->createGlueAuthenticationRequestContextTransfer()
            ->setRequestApplication(static::GLUE_BACKEND_API_APPLICATION);

        //Act
        $userAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin = $this->createUserAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin();
        $isApplicable = $userAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin->isApplicable(
            $oauthRequestTransfer,
            $glueAuthenticationRequestContextTransfer,
        );

        //Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testUserAuthCodeOauthRequestGrantTypeConfigurationProviderPluginIsNotApplicable(): void
    {
        //Arrange
        $oauthRequestTransfer = $this->tester->createOauthRequestTransfer();
        $glueAuthenticationRequestContextTransfer = $this->tester->createGlueAuthenticationRequestContextTransfer();

        //Act
        $userAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin = $this->createUserAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin();
        $isApplicable = $userAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin->isApplicable(
            $oauthRequestTransfer,
            $glueAuthenticationRequestContextTransfer,
        );

        //Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testUserAuthCodeOauthRequestGrantTypeConfigurationProviderPluginGetsGrantTypeConfiguration(): void
    {
        //Act
        $userAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin = $this->createUserAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin();
        $oauthGrantTypeConfiguration = $userAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin->getGrantTypeConfiguration();

        //Assert
        $this->assertInstanceOf(OauthGrantTypeConfigurationTransfer::class, $oauthGrantTypeConfiguration);
        $this->assertSame($this->tester::GRANT_TYPE_AUTHORIZATION_CODE, $oauthGrantTypeConfiguration->getIdentifier());
        $this->assertSame(UserAuthCodeGrantTypeBuilder::class, $oauthGrantTypeConfiguration->getBuilderFullyQualifiedClassName());
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRequestGrantTypeConfigurationProviderPluginInterface
     */
    protected function createUserAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin(): OauthRequestGrantTypeConfigurationProviderPluginInterface
    {
        return new UserAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin();
    }
}
