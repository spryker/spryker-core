<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Authorization;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class AuthorizationConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines if multiple authorization strategies can be executed during request.
     *
     * @api
     *
     * @deprecated Will be removed with next major. Multistrategy authorization is not supported anymore.
     *
     * @return bool
     */
    public function isMultistrategyAuthorizationAllowed(): bool
    {
        return false;
    }
}
