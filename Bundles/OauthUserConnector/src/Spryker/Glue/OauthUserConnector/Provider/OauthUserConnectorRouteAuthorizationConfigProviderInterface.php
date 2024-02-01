<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthUserConnector\Provider;

use Generated\Shared\Transfer\RouteAuthorizationConfigTransfer;

interface OauthUserConnectorRouteAuthorizationConfigProviderInterface
{
    /**
     * @return \Generated\Shared\Transfer\RouteAuthorizationConfigTransfer
     */
    public function provide(): RouteAuthorizationConfigTransfer;
}
