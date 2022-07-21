<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 * @method \Spryker\Zed\Customer\CustomerConfig getConfig()
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface getQueryContainer()
 */
class CustomerSalutationCheckoutPreConditionPlugin extends AbstractPlugin implements CheckoutPreConditionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `QuoteTransfer.customer` to be set.
     * - If `QuoteTransfer.customer.salutation` is set, checks if the salutation is present in a list of available salutations.
     * - If the salutation is invalid, sets `CheckoutResponseTransfer.isSuccess` equal to `false` and adds an error.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        return $this->getFacade()->validateCustomerCheckoutSalutation($quoteTransfer, $checkoutResponseTransfer);
    }
}
