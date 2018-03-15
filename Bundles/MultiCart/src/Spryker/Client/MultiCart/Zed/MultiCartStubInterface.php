<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\Zed;

use Generated\Shared\Transfer\QuoteActivatorRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface MultiCartStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteActivatorRequestTransfer $quoteActivatorRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setActiveQuote(QuoteActivatorRequestTransfer $quoteActivatorRequestTransfer): QuoteResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function duplicateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;
}
