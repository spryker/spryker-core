<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Checkout\Plugin\QuoteProceedCheckoutCheckPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\QuoteApproval\Plugin\Permission\PlaceOrderPermissionPlugin;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;

/**
 * @method \Spryker\Client\QuoteApproval\QuoteApprovalFactory getFactory()
 */
class QuoteApprovalProceedCheckoutCheckPlugin extends AbstractPlugin implements QuoteProceedCheckoutCheckPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function can(QuoteTransfer $quoteTransfer): bool
    {
        $permissionClient = $this->getFactory()->getPermissionClient();

        if (!$permissionClient->findCustomerPermissionByKey(PlaceOrderPermissionPlugin::KEY)) {
            return true;
        }

        if ($permissionClient->can(PlaceOrderPermissionPlugin::KEY, $quoteTransfer)) {
            return true;
        }

        $quoteApprovalStatus = $this->getFactory()
            ->createQuoteApprovalStatusCalculator()
            ->calculateQuoteStatus($quoteTransfer);

        return $quoteApprovalStatus === QuoteApprovalConfig::STATUS_APPROVED;
    }
}
