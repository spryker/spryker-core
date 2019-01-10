<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest;

use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class QuoteRequestConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getInitialStatus(): string
    {
        return SharedQuoteRequestConfig::STATUS_DRAFT;
    }
}
