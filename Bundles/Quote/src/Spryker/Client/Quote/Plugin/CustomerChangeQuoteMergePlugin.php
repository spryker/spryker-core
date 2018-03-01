<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Customer\Dependency\Plugin\CustomerSessionSetPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\Quote\QuoteClientInterface getClient()
 * @method \Spryker\Client\Quote\QuoteFactory getFactory()
 */
class CustomerChangeQuoteMergePlugin extends AbstractPlugin implements CustomerSessionSetPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function execute(CustomerTransfer $customerTransfer)
    {
        $this->getFactory()->createCustomerLoginQuoteMerger()->syncQuoteForCustomer($customerTransfer);
    }
}
