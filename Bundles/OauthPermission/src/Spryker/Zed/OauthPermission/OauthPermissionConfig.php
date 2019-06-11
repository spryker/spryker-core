<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthPermission;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class OauthPermissionConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getOauthUserIdentifierFilterKeys(): array
    {
        return [];
    }
}
