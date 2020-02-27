<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Business\Provider;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\OrderCustomReference\OrderCustomReferenceConfig;

class QuoteFieldsProvider implements QuoteFieldsProviderInterface
{
    /**
     * @var \Spryker\Zed\OrderCustomReference\OrderCustomReferenceConfig
     */
    protected $orderCustomReferenceConfig;

    /**
     * @param \Spryker\Zed\OrderCustomReference\OrderCustomReferenceConfig $orderCustomReferenceConfig
     */
    public function __construct(OrderCustomReferenceConfig $orderCustomReferenceConfig)
    {
        $this->orderCustomReferenceConfig = $orderCustomReferenceConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    public function getOrderCustomReferenceQuoteFieldsAllowedForSaving(QuoteTransfer $quoteTransfer): array
    {
        return $this->orderCustomReferenceConfig->getOrderCustomReferenceQuoteFieldsAllowedForSaving();
    }
}
