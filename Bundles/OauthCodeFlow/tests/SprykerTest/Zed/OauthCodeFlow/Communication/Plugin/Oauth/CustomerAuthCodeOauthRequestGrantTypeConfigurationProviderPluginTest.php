<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthCodeFlow\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer;
use Spryker\Zed\OauthCodeFlow\Business\Builders\CustomerAuthCodeGrantTypeBuilder;
use Spryker\Zed\OauthCodeFlow\Communication\Plugin\Oauth\CustomerAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRequestGrantTypeConfigurationProviderPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthCodeFlow
 * @group Communication
 * @group Plugin
 * @group CustomerAuthCodeOauthRequestGrantTypeConfigurationProviderPluginTest
 * Add your own group annotations below this line
 */
class CustomerAuthCodeOauthRequestGrantTypeConfigurationProviderPluginTest extends Unit
{
 /**
  * @uses \Spryker\Zed\OauthCodeFlow\Communication\Plugin\Oauth\CustomerAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin::GLUE_STOREFRONT_API_APPLICATION
  *
  * @var string
  */
    protected const GLUE_STOREFRONT_API_APPLICATION = 'GLUE_STOREFRONT_API_APPLICATION';

    /**
     * @var \SprykerTest\Zed\OauthCodeFlow\OauthCodeFlowCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCustomerAuthCodeOauthRequestGrantTypeConfigurationProviderPluginIsApplicable(): void
    {
        //Arrange
        $oauthRequestTransfer = $this->tester->createOauthRequestTransfer()
            ->setGrantType($this->tester::GRANT_TYPE_AUTHORIZATION_CODE);
        $glueAuthenticationRequestContextTransfer = $this->tester->createGlueAuthenticationRequestContextTransfer()
        ->setRequestApplication(static::GLUE_STOREFRONT_API_APPLICATION);

        //Act
        $customerAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin = $this->createCustomerAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin();
        $isApplicable = $customerAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin->isApplicable(
            $oauthRequestTransfer,
            $glueAuthenticationRequestContextTransfer,
        );

        //Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testCustomerAuthCodeOauthRequestGrantTypeConfigurationProviderPluginIsNotApplicable(): void
    {
        //Arrange
        $oauthRequestTransfer = $this->tester->createOauthRequestTransfer();
        $glueAuthenticationRequestContextTransfer = $this->tester->createGlueAuthenticationRequestContextTransfer();

        //Act
        $customerAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin = $this->createCustomerAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin();
        $isApplicable = $customerAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin->isApplicable(
            $oauthRequestTransfer,
            $glueAuthenticationRequestContextTransfer,
        );

        //Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testCustomerAuthCodeOauthRequestGrantTypeConfigurationProviderPluginGetsGrantTypeConfiguration(): void
    {
        //Act
        $customerAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin = $this->createCustomerAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin();
        $oauthGrantTypeConfiguration = $customerAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin->getGrantTypeConfiguration();

        //Assert
        $this->assertInstanceOf(OauthGrantTypeConfigurationTransfer::class, $oauthGrantTypeConfiguration);
        $this->assertSame($this->tester::GRANT_TYPE_AUTHORIZATION_CODE, $oauthGrantTypeConfiguration->getIdentifier());
        $this->assertSame(CustomerAuthCodeGrantTypeBuilder::class, $oauthGrantTypeConfiguration->getBuilderFullyQualifiedClassName());
    }

    /**
     * @return \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRequestGrantTypeConfigurationProviderPluginInterface
     */
    protected function createCustomerAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin(): OauthRequestGrantTypeConfigurationProviderPluginInterface
    {
        return new CustomerAuthCodeOauthRequestGrantTypeConfigurationProviderPlugin();
    }
}
