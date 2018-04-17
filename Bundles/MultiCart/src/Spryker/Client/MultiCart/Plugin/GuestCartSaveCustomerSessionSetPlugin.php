<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Customer\Dependency\Plugin\CustomerSessionSetPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\MultiCart\MultiCartClientInterface getClient()
 * @method \Spryker\Client\MultiCart\MultiCartFactory getFactory()
 */
class GuestCartSaveCustomerSessionSetPlugin extends AbstractPlugin implements CustomerSessionSetPluginInterface
{
    /**
     * Specification:
     * - Saves guest customer quote to database if it is not empty.
     * - Takes active customer quote from database if guest cart is empty.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function execute(CustomerTransfer $customerTransfer)
    {
        $this->getFactory()->createCustomerLoginQuoteSave()->syncQuoteForCustomer($customerTransfer);
    }
}
