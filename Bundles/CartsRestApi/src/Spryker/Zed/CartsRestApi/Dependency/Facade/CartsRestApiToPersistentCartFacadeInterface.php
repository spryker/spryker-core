<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Dependency\Facade;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;

interface CartsRestApiToPersistentCartFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteUpdateRequestTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(QuoteUpdateRequestTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function delete(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;
}
