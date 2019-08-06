<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Customer\Dependency\Plugin\CustomerSessionSetPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\PersistentCart\PersistentCartClientInterface getClient()
 * @method \Spryker\Client\PersistentCart\PersistentCartFactory getFactory()
 */
class GuestCartUpdateCustomerSessionSetPlugin extends AbstractPlugin implements CustomerSessionSetPluginInterface
{
    /**
     * {@inheritdoc}
     * - Executed if quote storage strategy is set to database, customer quote parameters customer and customer reference are empty.
     * - Makes zed call.
     * - Synchronizes customer session quote and customer quote from DB.
     * - Executes QuoteUpdatePluginExecutorInterface plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function execute(CustomerTransfer $customerTransfer)
    {
        $this->getFactory()->createCustomerLoginQuoteSync()->syncQuoteForCustomer($customerTransfer);
    }
}
