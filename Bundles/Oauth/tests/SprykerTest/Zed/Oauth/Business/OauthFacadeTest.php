<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oauth\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;
use Generated\Shared\Transfer\SpyOauthClientEntityTransfer;
use Generated\Shared\Transfer\SpyOauthScopeEntityTransfer;
use Orm\Zed\Oauth\Persistence\SpyOauthClient;
use Spryker\Zed\Oauth\Business\OauthFacadeInterface;
use Spryker\Zed\Oauth\Dependency\Plugin\UserProviderPluginInterface;
use Spryker\Zed\Oauth\OauthDependencyProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Oauth
 * @group Business
 * @group Facade
 * @group OauthFacadeTest
 * Add your own group annotations below this line
 */
class OauthFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Oauth\OauthBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testAccesTokenShouldReturnSuccessWhenValid(): void
    {
        $this->createTestClient();
        $this->setUserProviderPluginMock();

        $oauthRequestTransfer = $this->createOauthRequestTransfer();
        $oauthResponseTransfer = $this->getOauthFacade()->processAccessTokenRequest($oauthRequestTransfer);

        $this->assertTrue($oauthResponseTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testAccesTokenShouldReturfailureWhenClientCredentialsInValid(): void
    {
        $this->createTestClient();
        $this->setUserProviderPluginMock();

        $oauthRequestTransfer = new OauthRequestTransfer();
        $oauthRequestTransfer
            ->setGrantType('password')
            ->setClientId('frontend')
            ->setClientSecret('abc1232');

        $oauthResponseTransfer = $this->getOauthFacade()->processAccessTokenRequest($oauthRequestTransfer);

        $this->assertFalse($oauthResponseTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testValidateAccessTokenShouldSuccessWithValidToken(): void
    {
        $this->createTestClient();
        $this->setUserProviderPluginMock();

        $oauthRequestTransfer = $this->createOauthRequestTransfer();
        $oauthResponseTransfer = $this->getOauthFacade()->processAccessTokenRequest($oauthRequestTransfer);

        $oauthAccessTokenValidationRequestTransfer = new OauthAccessTokenValidationRequestTransfer();
        $oauthAccessTokenValidationRequestTransfer
            ->setAccessToken($oauthResponseTransfer->getAccessToken())
            ->setType('Bearer');

        $oauthAccessTokenValidationResponseTransfer = $this->getOauthFacade()
            ->validateAccessToken($oauthAccessTokenValidationRequestTransfer);

        $this->assertTrue($oauthAccessTokenValidationResponseTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testValidateAccessTokenShouldFailedWithInValidToken(): void
    {
        $oauthAccessTokenValidationRequestTransfer = new OauthAccessTokenValidationRequestTransfer();
        $oauthAccessTokenValidationRequestTransfer
            ->setAccessToken('wrong')
            ->setType('Bearer');

        $oauthAccessTokenValidationResponseTransfer = $this->getOauthFacade()
            ->validateAccessToken($oauthAccessTokenValidationRequestTransfer);

        $this->assertFalse($oauthAccessTokenValidationResponseTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testSaveScopeShouldPersist(): void
    {
        $spyOauthScopeEntityTransfer = (new SpyOauthScopeEntityTransfer())
            ->setIdentifier('identifier')
            ->setDescription('test scope');

        $spyOauthScopeEntityTransfer = $this->getOauthFacade()->saveScope($spyOauthScopeEntityTransfer);

        $this->assertNotEmpty($spyOauthScopeEntityTransfer->getIdOauthScope());
    }

    /**
     * @return void
     */
    public function testSaveClientShouldPersist(): void
    {
        $spyOauthClientEntityTransfer = (new SpyOauthClientEntityTransfer())
            ->setIdentifier('identifier')
            ->setName('client name')
            ->setSecret('secret')
            ->setIsConfidental(true)
            ->setRedirectUri('url');

        $spyOauthClientEntityTransfer = $this->getOauthFacade()->saveClient($spyOauthClientEntityTransfer);

        $this->assertNotEmpty($spyOauthClientEntityTransfer->getIdOauthClient());
    }

    /**
     * @return void
     */
    protected function setUserProviderPluginMock(): void
    {
        $userProviderPluginMock = $this->getMockBuilder(UserProviderPluginInterface::class)
            ->setMethods(['getUser', 'accept'])
            ->getMock();

        $userProviderPluginMock->method('getUser')->willReturnCallback(
            function (OauthUserTransfer $oauthUserTransfer) {
                $oauthUserTransfer->setIsSuccess(true)
                    ->setUserIdentifier(1);

                return $oauthUserTransfer;
            }
        );

        $userProviderPluginMock
            ->method('accept')
            ->willReturn(true);

        $this->tester->setDependency(
            OauthDependencyProvider::PLUGIN_USER_PROVIDER,
            [
                $userProviderPluginMock,
            ]
        );
    }

    /**
     * @return void
     */
    protected function createTestClient(): void
    {
        (new SpyOauthClient())
            ->setName('frontend api client')
            ->setIdentifier('frontend')
            ->setSecret('$2y$10$gkKxj9iHzIAtza98kT4Ipe0/bxHV1XIEvLROcqaC6YdHJThUFrexS')
            ->setIsConfidental(true)
            ->save();
    }

    /**
     * @return \Spryker\Zed\Oauth\Business\OauthFacadeInterface
     */
    protected function getOauthFacade(): OauthFacadeInterface
    {
        return $this->tester->getLocator()->oauth()->facade();
    }

    /**
     * @return \Generated\Shared\Transfer\OauthRequestTransfer
     */
    protected function createOauthRequestTransfer(): OauthRequestTransfer
    {
        $oauthRequestTransfer = new OauthRequestTransfer();
        $oauthRequestTransfer
            ->setGrantType('password')
            ->setClientId('frontend')
            ->setClientSecret('abc123')
            ->setUsername('spencor.hopkin@spryker.com')
            ->setPassword('change123');
        return $oauthRequestTransfer;
    }
}
