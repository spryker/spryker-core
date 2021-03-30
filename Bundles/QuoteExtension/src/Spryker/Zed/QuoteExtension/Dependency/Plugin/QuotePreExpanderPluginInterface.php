<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuotePreExpanderPluginInterface
{
    /**
     * Specification:
     * - Method is executed before {@link \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteExpanderPluginInterface::expand() }.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function preExpand(QuoteTransfer $quoteTransfer): void;
}
