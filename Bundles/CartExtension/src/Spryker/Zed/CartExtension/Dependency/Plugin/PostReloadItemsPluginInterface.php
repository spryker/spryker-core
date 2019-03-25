<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface PostReloadItemsPluginInterface
{
    /**
     * Specification:
     *   - This plugin is execute after reloading cart items, with this plugin you can modify quote after reloaded it.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function postReloadItems(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
