<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication;

use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConstants;

class GlueStorefrontApiApplicationConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns the host that the Storefront API application serves
     *
     * @api
     *
     * @return string
     */
    public function getStorefrontApiApplicationHost(): string
    {
        return $this->get(GlueStorefrontApiApplicationConstants::GLUE_STOREFRONT_API_HOST, '');
    }

    /**
     * Specification:
     * - Configures if api application should output debug statements
     *
     * @api
     *
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return (bool)$this->get(
            GlueStorefrontApiApplicationConstants::ENABLE_APPLICATION_DEBUG,
            false,
        );
    }
}
