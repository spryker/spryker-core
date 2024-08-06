<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\KernelApp;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\KernelApp\KernelAppConstants;

class KernelAppConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getTenantIdentifier(): string
    {
        return $this->get(KernelAppConstants::TENANT_IDENTIFIER);
    }
}
