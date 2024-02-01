<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthUserConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Generated\Shared\Transfer\UserIdentifierTransfer;
use Spryker\Zed\OauthUserConnector\OauthUserConnectorDependencyProvider;
use Spryker\Zed\OauthUserConnectorExtension\Dependency\Plugin\UserTypeOauthScopeProviderPluginInterface;
use SprykerTest\Zed\OauthUserConnector\OauthUserConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthUserConnector
 * @group Business
 * @group GetScopesTest
 * Add your own group annotations below this line
 */
class GetScopesTest extends Unit
{
    /**
     * @uses \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig::SCOPE_USER
     *
     * @var string
     */
    protected const SCOPE_USER = 'user';

    /**
     * @uses \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig::SCOPE_BACK_OFFICE_USER
     *
     * @var string
     */
    protected const SCOPE_BACK_OFFICE_USER = 'back-office-user';

    /**
     * @uses \Spryker\Zed\OauthWarehouseUser\OauthWarehouseUserConfig::SCOPE_WAREHOUSE_USER
     *
     * @var string
     */
    protected const SCOPE_WAREHOUSE_USER = 'warehouse-user';

    /**
     * @var int
     */
    protected const NUMBER_OF_DEFAULT_SCOPES = 2;

    /**
     * @var \SprykerTest\Zed\OauthUserConnector\OauthUserConnectorBusinessTester
     */
    protected OauthUserConnectorBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldReturnConfiguratedScopesWithDefaultBackOfficeScopeWhileNoScopesFromPlugins(): void
    {
        // Arrange
        $userIdentifierTransfer = (new UserIdentifierTransfer())->fromArray($this->tester->haveUser()->toArray(), true);
        $oauthScopeRequestTransfer = (new OauthScopeRequestTransfer())->setUserIdentifier(json_encode($userIdentifierTransfer->toArray()));

        // Act
        $oauthScopeTransfers = $this->tester->getFacade()->getScopes($oauthScopeRequestTransfer);

        //Assert
        $this->assertCount(static::NUMBER_OF_DEFAULT_SCOPES, $oauthScopeTransfers);
        $this->assertSame(
            [
                static::SCOPE_USER,
                static::SCOPE_BACK_OFFICE_USER,
            ],
            array_map(
                function (OauthScopeTransfer $oauthScopeTransfer) {
                    return $oauthScopeTransfer->getIdentifier();
                },
                $oauthScopeTransfers,
            ),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnConfiguratedScopesWithScopesFromPlugins(): void
    {
        // Arrange
        $userIdentifierTransfer = (new UserIdentifierTransfer())->fromArray($this->tester->haveUser()->toArray(), true);
        $oauthScopeRequestTransfer = (new OauthScopeRequestTransfer())->setUserIdentifier(json_encode($userIdentifierTransfer->toArray()));

        $this->tester->setDependency(OauthUserConnectorDependencyProvider::PLUGINS_USER_TYPE_OAUTH_SCOPE_PROVIDER, [
            $this->createUserTypeOauthScopeProviderPluginMock(),
        ]);

        // Act
        $oauthScopeTransfers = $this->tester->getFacade()->getScopes($oauthScopeRequestTransfer);

        //Assert
        $this->assertCount(static::NUMBER_OF_DEFAULT_SCOPES, $oauthScopeTransfers);
        $this->assertSame(
            [
                static::SCOPE_USER,
                static::SCOPE_WAREHOUSE_USER,
            ],
            array_map(
                function (OauthScopeTransfer $oauthScopeTransfer) {
                    return $oauthScopeTransfer->getIdentifier();
                },
                $oauthScopeTransfers,
            ),
        );
    }

    /**
     * @return \SprykerTest\Zed\OauthUserConnector\Business\UserTypeOauthScopeProviderPluginInterface
     */
    protected function createUserTypeOauthScopeProviderPluginMock(): UserTypeOauthScopeProviderPluginInterface
    {
        $userTypeOauthScopeProviderPluginMock = $this->createMock(UserTypeOauthScopeProviderPluginInterface::class);

        $userTypeOauthScopeProviderPluginMock->method('getScopes')->willReturn([
            (new OauthScopeTransfer())->setIdentifier(static::SCOPE_WAREHOUSE_USER),
        ]);

        return $userTypeOauthScopeProviderPluginMock;
    }
}
