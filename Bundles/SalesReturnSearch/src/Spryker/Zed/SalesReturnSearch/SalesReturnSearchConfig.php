<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnSearch;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesReturnSearchConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string|null
     */
    public function getReturnReasonSearchSynchronizationPoolName(): ?string
    {
        return null;
    }
}
