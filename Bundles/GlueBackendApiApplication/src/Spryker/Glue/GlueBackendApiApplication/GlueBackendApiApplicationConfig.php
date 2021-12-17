<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication;

use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\GlueBackendApiApplication\GlueBackendApiApplicationConstants;

class GlueBackendApiApplicationConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns the host that the Backend API application serves
     *
     * @api
     *
     * @return string
     */
    public function getBackendApiApplicationHost(): string
    {
        return $this->get(GlueBackendApiApplicationConstants::GLUE_BACKEND_API_HOST, '');
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
            GlueBackendApiApplicationConstants::ENABLE_APPLICATION_DEBUG,
            false,
        );
    }
}
