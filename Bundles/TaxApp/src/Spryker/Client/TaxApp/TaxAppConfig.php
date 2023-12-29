<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\TaxApp;

use Spryker\Client\Kernel\AbstractBundleConfig;

class TaxAppConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const REQUEST_TIMEOUT_SECONDS = 5;

    /**
     * @api
     *
     * @return int
     */
    public function getRequestTimeoutInSeconds(): int
    {
        return static::REQUEST_TIMEOUT_SECONDS;
    }
}
