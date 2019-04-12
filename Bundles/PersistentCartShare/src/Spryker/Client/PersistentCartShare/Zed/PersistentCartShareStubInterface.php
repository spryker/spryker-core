<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare\Zed;

use Generated\Shared\Transfer\QuotePreviewRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface PersistentCartShareStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuotePreviewRequestTransfer $quotePreviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function getQuoteForPreview(QuotePreviewRequestTransfer $quotePreviewRequestTransfer): QuoteResponseTransfer;
}
