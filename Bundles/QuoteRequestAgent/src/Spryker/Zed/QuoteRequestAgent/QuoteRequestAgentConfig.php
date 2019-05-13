<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestAgent;

use Spryker\Shared\QuoteRequestAgent\QuoteRequestAgentConfig as SharedQuoteRequestAgentConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\QuoteRequestAgent\QuoteRequestAgentConfig getSharedConfig()
 */
class QuoteRequestAgentConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getInitialStatus(): string
    {
        return SharedQuoteRequestAgentConfig::STATUS_IN_PROGRESS;
    }
}
