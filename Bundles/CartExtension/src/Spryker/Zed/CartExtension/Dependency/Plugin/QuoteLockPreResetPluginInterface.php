<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteLockPreResetPluginInterface
{
    /**
     * Specification:
     * - Plugins which are called before quote lock reset.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
