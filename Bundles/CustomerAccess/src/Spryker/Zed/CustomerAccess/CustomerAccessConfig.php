<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CustomerAccessConfig extends AbstractBundleConfig
{
    const CONTENT_TYPE_PRICE = 'price';

    /**
     * @return array
     */
    public function getContentTypes()
    {
        return [];
    }

    /**
     * @return bool
     */
    public function getDefaultContentTypeAccess()
    {
        return false;
    }
}
