<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthUserConnector\Provider;

use Generated\Shared\Transfer\RouteAuthorizationConfigTransfer;
use Symfony\Component\HttpFoundation\Response;

class OauthUserConnectorRouteAuthorizationConfigProvider implements OauthUserConnectorRouteAuthorizationConfigProviderInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_UNAUTHORIZED_REQUEST = 'Unauthorized request.';

    /**
     * @uses \Spryker\Zed\OauthUserConnector\Communication\Plugin\Authorization\OauthUserScopeAuthorizationStrategyPlugin::STRATEGY_NAME
     *
     * @var string
     */
    protected const STRATEGY_NAME = 'UserOauthScope';

    /**
     * @return \Generated\Shared\Transfer\RouteAuthorizationConfigTransfer
     */
    public function provide(): RouteAuthorizationConfigTransfer
    {
        return (new RouteAuthorizationConfigTransfer())
            ->addStrategy(static::STRATEGY_NAME)
            ->setApiMessage(static::ERROR_MESSAGE_UNAUTHORIZED_REQUEST)
            ->setHttpStatusCode(Response::HTTP_FORBIDDEN);
    }
}
