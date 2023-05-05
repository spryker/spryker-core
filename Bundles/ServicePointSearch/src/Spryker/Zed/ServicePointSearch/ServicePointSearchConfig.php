<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointSearch;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ServicePointSearchConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string|null
     */
    public function getServicePointSearchSynchronizationPoolName(): ?string
    {
        return null;
    }
}
