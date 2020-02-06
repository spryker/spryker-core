<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Business\Provider;

use Generated\Shared\Transfer\QuoteTransfer;

class QuoteFieldsProvider implements QuoteFieldsProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    public function getOrderCustomReferenceQuoteFieldsAllowedForSaving(QuoteTransfer $quoteTransfer): array
    {
        return [
            QuoteTransfer::ORDER_CUSTOM_REFERENCE,
        ];
    }
}
