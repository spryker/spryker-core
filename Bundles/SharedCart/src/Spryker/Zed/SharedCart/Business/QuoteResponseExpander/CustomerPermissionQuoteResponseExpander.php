<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\QuoteResponseExpander;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToCustomerFacadeInterface;

class CustomerPermissionQuoteResponseExpander implements QuoteResponseExpanderInterface
{
    /**
     * @var \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToCustomerFacadeInterface $customerFacade
     */
    public function __construct(SharedCartToCustomerFacadeInterface $customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function expand(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        $customerTransfer = $quoteResponseTransfer->getQuoteTransfer()->requireCustomer()->getCustomer();
        $customerTransfer->setPermissions(null);
        $permissionCollectionTransfer = $this->customerFacade->getCustomer($customerTransfer)->getPermissions();
        $quoteResponseTransfer->setCustomerPermissions($permissionCollectionTransfer);

        return $quoteResponseTransfer;
    }
}
