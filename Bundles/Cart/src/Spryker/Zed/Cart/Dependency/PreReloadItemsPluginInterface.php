<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Dependency;

use Generated\Shared\Transfer\QuoteTransfer;

interface PreReloadItemsPluginInterface
{
    /**
     *  Specification:
     *   - This plugin is execute before reloading cart items, with this plugin you can modify quote before reloading it.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preReloadItems(QuoteTransfer $quoteTransfer);
}
