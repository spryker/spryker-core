<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\OauthUserConnector\Plugin\GlueBackendApiApplicationAuthorizationConnector;

use Codeception\Test\Unit;
use Spryker\Glue\OauthUserConnector\Plugin\GlueBackendApiApplicationAuthorizationConnector\OauthUserScopeProtectedRouteAuthorizationConfigProviderPlugin;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group OauthUserConnector
 * @group Plugin
 * @group GlueBackendApiApplicationAuthorizationConnector
 * @group OauthUserScopeProtectedRouteAuthorizationConfigProviderPluginTest
 * Add your own group annotations below this line
 */
class OauthUserScopeProtectedRouteAuthorizationConfigProviderPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\OauthUserConnector\Communication\Plugin\Authorization\OauthUserScopeAuthorizationStrategyPlugin::STRATEGY_NAME
     *
     * @var string
     */
    protected const STRATEGY_NAME = 'UserOauthScope';

    /**
     * @uses \Spryker\Glue\OauthUserConnector\Provider\OauthUserConnectorRouteAuthorizationConfigProvider::ERROR_MESSAGE_UNAUTHORIZED_REQUEST
     *
     * @var string
     */
    protected const ERROR_MESSAGE_UNAUTHORIZED_REQUEST = 'Unauthorized request.';

    /**
     * @return void
     */
    public function testProvideShouldReturnRouteAuthorizationConfigTransferWithCorrectProperties(): void
    {
        // Arrange
        $oauthUserScopeProtectedRouteAuthorizationConfigProviderPlugin = new OauthUserScopeProtectedRouteAuthorizationConfigProviderPlugin();

        // Act
        $routeAuthorizationConfigTransfer = $oauthUserScopeProtectedRouteAuthorizationConfigProviderPlugin->provide();

        // Assert
        $this->assertSame($routeAuthorizationConfigTransfer->getStrategies(), [static::STRATEGY_NAME]);
        $this->assertSame($routeAuthorizationConfigTransfer->getApiMessage(), static::ERROR_MESSAGE_UNAUTHORIZED_REQUEST);
        $this->assertSame($routeAuthorizationConfigTransfer->getHttpStatusCode(), Response::HTTP_FORBIDDEN);
    }
}
