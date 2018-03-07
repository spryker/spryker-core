<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Customer\Dependency\Plugin\CustomerSessionSetPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\Cart\CartClientInterface getClient()
 * @method \Spryker\Client\Cart\CartFactory getFactory()
 */
class CustomerChangeCartUpdatePlugin extends AbstractPlugin implements CustomerSessionSetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function execute(CustomerTransfer $customerTransfer)
    {
        $quoteTransfer = $this->getClient()->getQuote();
        $quoteTransfer->setCustomer($customerTransfer);

        $this->getFactory()->getQuoteClient()->setQuote($quoteTransfer);
    }
}
