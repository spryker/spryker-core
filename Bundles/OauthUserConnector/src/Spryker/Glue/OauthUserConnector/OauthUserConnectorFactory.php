<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthUserConnector;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\OauthUserConnector\Provider\OauthUserConnectorRouteAuthorizationConfigProvider;
use Spryker\Glue\OauthUserConnector\Provider\OauthUserConnectorRouteAuthorizationConfigProviderInterface;

class OauthUserConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\OauthUserConnector\Provider\OauthUserConnectorRouteAuthorizationConfigProviderInterface
     */
    public function createOauthUserConnectorRouteAuthorizationConfigProvider(): OauthUserConnectorRouteAuthorizationConfigProviderInterface
    {
        return new OauthUserConnectorRouteAuthorizationConfigProvider();
    }
}
