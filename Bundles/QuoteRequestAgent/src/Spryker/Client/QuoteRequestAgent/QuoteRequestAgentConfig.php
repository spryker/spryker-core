<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequestAgent;

use Spryker\Client\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\QuoteRequestAgent\QuoteRequestAgentConfig getSharedConfig()
 */
class QuoteRequestAgentConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return array<string>
     */
    public function getCancelableStatuses(): array
    {
        return $this->getSharedConfig()->getCancelableStatuses();
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getRevisableStatuses(): array
    {
        return $this->getSharedConfig()->getRevisableStatuses();
    }
}
