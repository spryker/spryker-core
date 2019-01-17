<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\Plugin\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CheckoutExtension\Dependency\Plugin\QuoteProceedCheckoutCheckPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\QuoteApproval\QuoteApprovalClient getClient()
 */
class QuoteApprovalProceedCheckoutCheckPlugin extends AbstractPlugin implements QuoteProceedCheckoutCheckPluginInterface
{
    /**
     * {@inheritdoc}
     * - Returns true if customer does't have PlaceOrderPermissionPlugin permission assigned.
     * - Returns true if excecuting of PlaceOrderPermissionPlugin permission returns true.
     * - Returns true if quote approval status is `approved`.
     * - Returns false othervise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function can(QuoteTransfer $quoteTransfer): bool
    {
        return !$this->getClient()->isQuoteRequireApproval($quoteTransfer);
    }
}
