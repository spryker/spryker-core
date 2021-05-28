<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOption\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantProductOption\Business\MerchantProductOptionFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOption\MerchantProductOptionConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOption\Communication\MerchantProductOptionCommunicationFactory getFactory()
 */
class MerchantProductOptionCheckoutPreConditionPlugin extends AbstractPlugin implements CheckoutPreConditionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks the approval status for merchant product option groups.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        /** @var bool $isSuccess */
        $isSuccess = $this->getFacade()
            ->validateMerchantProductOptionsOnCheckout($quoteTransfer, $checkoutResponseTransfer)
            ->getIsSuccess();

        return $isSuccess;
    }
}
