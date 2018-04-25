<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CustomerAccessConfig extends AbstractBundleConfig
{
    public const CONTENT_TYPE_PRICE = 'price';

    /**
     * @return array
     */
    public function getContentTypes(): array
    {
        return [];
    }

    /**
     * @return bool
     */
    public function getDefaultContentTypeAccess(): bool
    {
        return false;
    }
}
