<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCustomerConnector\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\CartCustomerConnector\CartCustomerConnectorFactory;
use Spryker\Client\Customer\Dependency\Plugin\CustomerSessionSetPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method CartCustomerConnectorFactory getFactory()
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
        $cartClient = $this->getFactory()->getCartClient();

        $quoteTransfer = $cartClient->getQuote();
        $quoteTransfer->setCustomer($customerTransfer);

        $cartClient->storeQuote($quoteTransfer);
    }
}
