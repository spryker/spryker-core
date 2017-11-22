<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Cart\CartClientInterface;
use Spryker\Client\Customer\Dependency\Plugin\CustomerSessionSetPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method CartClientInterface getClient()
 */
class CustomerChangeCartUpdatePlugin extends AbstractPlugin implements CustomerSessionSetPluginInterface
{
    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function execute(CustomerTransfer $customerTransfer)
    {
        $quoteTransfer = $this->getClient()->getQuote();
        $quoteTransfer->setCustomer($customerTransfer);

        $this->getClient()->storeQuote($quoteTransfer);
    }
}
