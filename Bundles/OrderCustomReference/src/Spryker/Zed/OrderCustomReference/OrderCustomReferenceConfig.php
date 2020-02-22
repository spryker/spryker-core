<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class OrderCustomReferenceConfig extends AbstractBundleConfig
{
    /**
     * @return string[]
     */
    public function getOrderCustomReferenceQuoteFieldsAllowedForSaving(): array
    {
        return [
            QuoteTransfer::ORDER_CUSTOM_REFERENCE,
        ];
    }
}
