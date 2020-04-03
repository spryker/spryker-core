<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oauth\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthClientTransfer;
use Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer;
use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;
use Orm\Zed\Oauth\Persistence\SpyOauthClient;
use Orm\Zed\Oauth\Persistence\SpyOauthClientQuery;
use Orm\Zed\OauthRevoke\Persistence\SpyOauthRefreshToken;
use Spryker\Zed\Oauth\Business\Model\League\Grant\PasswordGrantType;
use Spryker\Zed\Oauth\Business\OauthFacade;
use Spryker\Zed\Oauth\Dependency\Facade\OauthToOauthRevokeFacadeBridge;
use Spryker\Zed\Oauth\OauthDependencyProvider;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthGrantTypeConfigurationProviderPluginInterface;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserProviderPluginInterface;

/**
 * Auto-generated group annotations
 *
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
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var \Spryker\Zed\Oauth\Business\OauthFacadeInterface
     */
    protected $oauthFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->oauthFacade = new OauthFacade();

        $this->customerTransfer = $this->tester->haveCustomer();
    }

    /**
     * @return void
     */
    public function testAccessTokenShouldReturnSuccessWhenValid(): void
    {
        $this->createTestClient();
        $this->setUserProviderPluginMock();
        $this->setGrantTypeConfigurationProviderPluginMock();

        $oauthRequestTransfer = $this->createOauthRequestTransfer();
        $oauthResponseTransfer = $this->oauthFacade->processAccessTokenRequest($oauthRequestTransfer);

        $this->assertTrue($oauthResponseTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testAccessTokenShouldReturnFailureWhenClientCredentialsInValid(): void
    {
        $this->createTestClient();
        $this->setUserProviderPluginMock();

        $oauthRequestTransfer = new OauthRequestTransfer();
        $oauthRequestTransfer
            ->setGrantType('password')
            ->setClientId('frontend')
            ->setClientSecret('abc1232');

        $oauthResponseTransfer = $this->oauthFacade->processAccessTokenRequest($oauthRequestTransfer);

        $this->assertFalse($oauthResponseTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testValidateAccessTokenShouldSuccessWithValidToken(): void
    {
        $this->createTestClient();
        $this->setUserProviderPluginMock();
        $this->setGrantTypeConfigurationProviderPluginMock();

        $oauthRequestTransfer = $this->createOauthRequestTransfer();
        $oauthResponseTransfer = $this->oauthFacade->processAccessTokenRequest($oauthRequestTransfer);

        $oauthAccessTokenValidationRequestTransfer = new OauthAccessTokenValidationRequestTransfer();
        $oauthAccessTokenValidationRequestTransfer
            ->setAccessToken($oauthResponseTransfer->getAccessToken())
            ->setType('Bearer');

        $oauthAccessTokenValidationResponseTransfer = $this->oauthFacade
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

        $oauthAccessTokenValidationResponseTransfer = $this->oauthFacade
            ->validateAccessToken($oauthAccessTokenValidationRequestTransfer);

        $this->assertFalse($oauthAccessTokenValidationResponseTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testSaveScopeShouldPersist(): void
    {
        $oauthScopeTransfer = (new OauthScopeTransfer())
            ->setIdentifier('identifier')
            ->setDescription('test scope');

        $oauthScopeTransfer = $this->oauthFacade->saveScope($oauthScopeTransfer);

        $this->assertNotEmpty($oauthScopeTransfer->getIdOauthScope());
    }

    /**
     * @return void
     */
    public function testSaveClientShouldPersist(): void
    {
        $oauthClientTransfer = (new OauthClientTransfer())
            ->setIdentifier('identifier')
            ->setName('client name')
            ->setSecret('secret')
            ->setIsConfidential(true)
            ->setRedirectUri('url');

        $oauthClientTransfer = $this->oauthFacade->saveClient($oauthClientTransfer);

        $this->assertNotEmpty($oauthClientTransfer->getIdOauthClient());
    }

    /**
     * @return void
     */
    public function testFindClientByIdentifier(): void
    {
        $oauthClientTransfer = (new OauthClientTransfer())
            ->setIdentifier('identifier')
            ->setName('client name')
            ->setSecret('secret')
            ->setIsConfidential(true)
            ->setRedirectUri('url');

        $this->oauthFacade->saveClient($oauthClientTransfer);

        $oauthClientTransfer = $this->oauthFacade->findClientByIdentifier($oauthClientTransfer);

        $this->assertNotNull($oauthClientTransfer);
    }

    /**
     * @return void
     */
    public function testFindScopeByIdentifier(): void
    {
        $oauthScopeTransfer = (new OauthScopeTransfer())
            ->setIdentifier('identifier')
            ->setDescription('test scope');

        $this->oauthFacade->saveScope($oauthScopeTransfer);

        $oauthScopeTransfer = $this->oauthFacade->findScopeByIdentifier($oauthScopeTransfer);

        $this->assertNotEmpty($oauthScopeTransfer->getIdOauthScope());
    }

    /**
     * @return void
     */
    public function testFindScopesByIdentifiers(): void
    {
        $identifiers = ['identifier', 'test_identifier'];

        $this->oauthFacade->saveScope(
            (new OauthScopeTransfer())
            ->setIdentifier($identifiers[0])
            ->setDescription('scope')
        );

        $this->oauthFacade->saveScope(
            (new OauthScopeTransfer())
                ->setIdentifier($identifiers[1])
                ->setDescription('test scope')
        );

        $oauthScopeTransfers = $this->oauthFacade->getScopesByIdentifiers($identifiers);

        foreach ($oauthScopeTransfers as $oauthScopeTransfer) {
            $this->assertNotEmpty($oauthScopeTransfer->getIdOauthScope());
        }
    }

    /**
     * @return void
     */
    public function testRevokeRefreshTokenShouldSuccessWithValidToken(): void
    {
        // Arrange
        $this->tester->deleteAllOauthRefreshTokens();
        $this->getOauthToOauthRevokeFacadeBridgeMock();
        $this->setUserProviderPluginMock();
        $customerTransfer = $this->tester->createCustomerTransfer();
        $oauthResponseTransfer = $this->tester->haveAuthorizationToGlue($customerTransfer);

        $revokeRefreshTokenRequestTransfer = $this->tester->createRevokeRefreshTokenRequestTransfer(
            $oauthResponseTransfer->getCustomerReference(),
            $oauthResponseTransfer->getRefreshToken()
        );

        // Act
        $revokerRefreshTokenResponseTransfer = $this->oauthFacade->revokeRefreshToken($revokeRefreshTokenRequestTransfer);

        // Assert
        $this->assertTrue($revokerRefreshTokenResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testRevokeRefreshTokenShouldFailedWithInvalidToken(): void
    {
        // Arrange
        $revokeRefreshTokenRequestTransfer = $this->tester->createRevokeRefreshTokenRequestTransfer(
            $this->tester->haveCustomer()->getCustomerReference(),
            'test'
        );

        // Act
        $revokerRefreshTokenResponseTransfer = $this->oauthFacade->revokeRefreshToken($revokeRefreshTokenRequestTransfer);

        // Assert
        $this->assertFalse($revokerRefreshTokenResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testRevokeRefreshTokensShouldSuccessWithValidCustomer(): void
    {
        // Arrange
        $revokeRefreshTokenRequestTransfer = $this->tester->createRevokeRefreshTokenRequestTransfer(
            $this->tester->haveCustomer()->getCustomerReference()
        );

        // Act
        $revokerRefreshTokenResponseTransfer = $this->oauthFacade->revokeAllRefreshTokens($revokeRefreshTokenRequestTransfer);

        // Assert
        $this->assertTrue($revokerRefreshTokenResponseTransfer->getIsSuccessful());
    }

    /**
     * @return array
     */
    public function dependencyOfRefreshTokenRetentionIntervalToRefreshTokenCountDataProvider()
    {
        return [
            ['-2 month', 0],
            ['+2 month', 1],
        ];
    }

    /**
     * @dataProvider dependencyOfRefreshTokenRetentionIntervalToRefreshTokenCountDataProvider
     *
     * @param string $interval
     * @param int $matches
     *
     * @return void
     */
    public function testDeleteExpiredRefreshTokens(string $interval, int $matches): void
    {
        // Arrange
        $this->tester->deleteAllOauthRefreshTokens();
        $this->setUserProviderPluginMock();
        $customerTransfer = $this->tester->createCustomerTransfer();
        $this->tester->haveAuthorizationToGlue($customerTransfer);

        $oauthClient = new SpyOauthClient();
        $oauthClient
            ->setName('test')
            ->setIdentifier('test')
            ->save();

        $expectedOauthRefreshToken = new SpyOauthRefreshToken();
        $expectedOauthRefreshToken
            ->setIdentifier('test')
            ->setUserIdentifier('test')
            ->setFkOauthClient($oauthClient->getIdentifier())
            ->setCustomerReference('test')
            ->setExpiresAt((new DateTime($interval))->format('Y-m-d'))
            ->save();

        // Act
        $this->oauthFacade->deleteExpiredRefreshTokens();

        // Assert
        $this->assertEquals($matches, $this->tester->getOauthRefreshTokensCount());
    }

    /**
     * @return void
     */
    protected function setUserProviderPluginMock(): void
    {
        $userProviderPluginMock = $this->getMockBuilder(OauthUserProviderPluginInterface::class)
            ->setMethods(['getUser', 'accept'])
            ->getMock();

        $userProviderPluginMock->method('getUser')->willReturnCallback(
            function (OauthUserTransfer $oauthUserTransfer) {
                $oauthUserTransfer->setIsSuccess(true)
                    ->setUserIdentifier(
                        json_encode(
                            (new CustomerIdentifierTransfer())
                                ->setCustomerReference('DE--test')
                                ->setIdCustomer(999)
                                ->toArray()
                        )
                    );

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
    protected function setGrantTypeConfigurationProviderPluginMock(): void
    {
        $grantTypeConfigurationProviderPluginMock = $this->getMockBuilder(OauthGrantTypeConfigurationProviderPluginInterface::class)
            ->setMethods(['getGrantTypeConfiguration'])
            ->getMock();

        $grantTypeConfigurationProviderPluginMock->method('getGrantTypeConfiguration')->willReturnCallback(
            function () {
                $oauthGrantTypeConfigurationTransfer = (new OauthGrantTypeConfigurationTransfer())
                    ->setIdentifier('password')
                    ->setFullyQualifiedClassName(PasswordGrantType::class);

                return $oauthGrantTypeConfigurationTransfer;
            }
        );

        $this->tester->setDependency(
            OauthDependencyProvider::PLUGINS_GRANT_TYPE_CONFIGURATION_PROVIDER,
            [
                $grantTypeConfigurationProviderPluginMock,
            ]
        );
    }

    /**
     * @return void
     */
    protected function createTestClient(): void
    {
        $oauthClientEntity = SpyOauthClientQuery::create()
            ->filterByIdentifier('frontend')
            ->findOneOrCreate();

        $oauthClientEntity
            ->setName('frontend api client')
            ->setSecret('$2y$10$gkKxj9iHzIAtza98kT4Ipe0/bxHV1XIEvLROcqaC6YdHJThUFrexS')
            ->setIsConfidential(true)
            ->save();
    }

    /**
     * @return void
     */
    protected function getOauthToOauthRevokeFacadeBridgeMock(): void
    {
        $oauthRefreshTokenTransfer = new OauthRefreshTokenTransfer();
        $oauthRefreshTokenTransfer->setIdentifier('test-identifier');

        $oauthRevokeFacade = $this->createMock(OauthToOauthRevokeFacadeBridge::class);
        $oauthRevokeFacade
            ->method('findRefreshToken')->willReturn($oauthRefreshTokenTransfer);

        $this->tester->setDependency(OauthDependencyProvider::FACADE_OAUTH_REVOKE, $oauthRevokeFacade);
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
