<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteExtension\Dependency\Plugin;

interface QuotePostExpanderPluginInterface
{
    /**
     * Specification:
     * - Use this method to clean-up plugin state after execution.
     *
     * @api
     *
     * @return void
     */
    public function postExpand(): void;
}
