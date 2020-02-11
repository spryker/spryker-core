<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AuthRestApi\Business;

use Codeception\Test\Unit;
use Spryker\Zed\Oauth\OauthDependencyProvider;
use Spryker\Zed\OauthCustomerConnector\Communication\Plugin\Oauth\CustomerOauthUserProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AuthRestApi
 * @group Business
 * @group Facade
 * @group AuthRestApiFacadeTest
 * Add your own group annotations below this line
 */
class AuthRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\AuthRestApi\AuthRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setUserProvider();
    }

    /**
     * @return void
     */
    public function testProcessAccessTokenWillGetValidOauthResponseTransfer(): void
    {
        // Arrange
        $authRestApiFacade = $this->tester->getFacade();
        $oauthRequestTransfer = $this->tester->prepareOauthRequestTransfer();

        // Act
        $oauthResponseTransfer = $authRestApiFacade->createAccessToken($oauthRequestTransfer);

        // Assert
        $this->assertEquals($oauthResponseTransfer->getAnonymousCustomerReference(), $oauthRequestTransfer->getCustomerReference());
        $this->assertTrue($oauthResponseTransfer->getIsValid());
        $this->assertNotEmpty($oauthResponseTransfer->getAccessToken());
    }

    /**
     * @return void
     */
    public function testProcessAccessTokenWillGetInvalidOauthResponseTransfer(): void
    {
        // Arrange
        $authRestApiFacade = $this->tester->getFacade();
        $oauthRequestTransfer = $this->tester->prepareOauthRequestTransferWithoutCustomerData();

        // Act
        $oauthResponseTransfer = $authRestApiFacade->createAccessToken($oauthRequestTransfer);

        // Assert
        $this->assertFalse($oauthResponseTransfer->getIsValid());
        $this->assertEmpty($oauthResponseTransfer->getAccessToken());
        $this->assertEmpty($oauthResponseTransfer->getCustomerReference());
    }

    /**
     * @return void
     */
    protected function setUserProvider(): void
    {
        $this->tester->setDependency(OauthDependencyProvider::PLUGIN_USER_PROVIDER, [
            new CustomerOauthUserProviderPlugin(),
        ]);
    }
}
