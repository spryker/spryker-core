<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnector\Business;

use Generated\Shared\Transfer\CustomerUserConnectionUpdateTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CustomerUserConnector\Business\CustomerUserConnectorBusinessFactory getFactory()
 */
class CustomerUserConnectorFacade extends AbstractFacade implements CustomerUserConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerUserConnectionUpdateTransfer $customerUserConnectionUpdateTransfer
     *
     * @return bool
     */
    public function updateCustomerUserConnection(CustomerUserConnectionUpdateTransfer $customerUserConnectionUpdateTransfer)
    {
        return $this->getFactory()
            ->createCustomerUserConnectionUpdater()
            ->updateCustomerUserConnection($customerUserConnectionUpdateTransfer);
    }
}
