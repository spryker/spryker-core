<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GlueApplicationAuthorizationConnector;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class GlueApplicationAuthorizationConnectorConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const ATTRIBUTE_ROUTE_AUTHORIZATION_CONFIGURATIONS = 'route-authorization-configurations';

    /**
     * @var string
     */
    public const ATTRIBUTE_ROUTE_AUTHORIZATION_DEFAULT_CONFIGURATION = 'route-authorization-default-configuration';
}
