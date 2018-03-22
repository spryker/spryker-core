<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Communication\Plugin;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PersistentCart\Dependency\Plugin\QuoteResponseExpanderPluginInterface;

/**
 * @method \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface getFacade()
 * @method \Spryker\Zed\SharedCart\Communication\SharedCartCommunicationFactory getFactory()
 */
class CustomerQuoteResponseExpanderPlugin extends AbstractPlugin implements QuoteResponseExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function expand(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        if (!$quoteResponseTransfer->getQuoteTransfer()) {
            return $quoteResponseTransfer;
        }
        $customerTransfer = $quoteResponseTransfer->getQuoteTransfer()->requireCustomer()->getCustomer();
        $permissionCollectionTransfer = $this->getFactory()->getCustomerFacade()
            ->getCustomer($customerTransfer)->getPermissions();
        $quoteResponseTransfer->setCustomerPermissions($permissionCollectionTransfer);

        return $this->getFacade()->expandQuoteResponseWithSharedCarts($quoteResponseTransfer);
    }
}
