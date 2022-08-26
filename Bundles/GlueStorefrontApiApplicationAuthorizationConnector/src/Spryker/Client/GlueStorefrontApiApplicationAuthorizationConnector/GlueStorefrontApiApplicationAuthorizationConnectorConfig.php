<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector;

use Spryker\Client\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorConfig getSharedConfig()
 */
class GlueStorefrontApiApplicationAuthorizationConnectorConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns a list of protected endpoints.
     *
     * @api
     *
     * @return array<string, mixed>
     */
    public function getProtectedPaths(): array
    {
        return $this->getSharedConfig()->getProtectedPaths();
    }
}
