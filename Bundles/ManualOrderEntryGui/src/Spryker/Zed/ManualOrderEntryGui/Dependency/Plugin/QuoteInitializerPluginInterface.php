<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Symfony\Component\HttpFoundation\Request;

interface QuoteInitializerPluginInterface
{
    /**
     * Communication layer plugins which allow to start manual order entry with an existing quote
     *
     * Specification:
     * - Uses request to define an initial state of a quote
     * - If a plugin is not able to init a quote it must return NULL
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function initializeQuote(Request $request): ?QuoteTransfer;
}
