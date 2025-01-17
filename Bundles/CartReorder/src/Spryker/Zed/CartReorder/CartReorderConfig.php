<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartReorder;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CartReorderConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const DEFAULT_QUOTE_PROCESS_FLOW_NAME = 'default';

    /**
     * Specification:
     * - Returns the default name of quote process flow.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultQuoteProcessFlowName(): string
    {
        return static::DEFAULT_QUOTE_PROCESS_FLOW_NAME;
    }
}
