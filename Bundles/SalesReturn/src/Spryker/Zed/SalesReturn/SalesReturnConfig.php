<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesReturnConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getReturnReferenceFormat(): string
    {
        return '%s-R%s';
    }
}
