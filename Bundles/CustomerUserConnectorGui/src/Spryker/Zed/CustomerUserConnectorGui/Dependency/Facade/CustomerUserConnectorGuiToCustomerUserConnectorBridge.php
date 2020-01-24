<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui\Dependency\Facade;

use Generated\Shared\Transfer\CustomerUserConnectionUpdateTransfer;

class CustomerUserConnectorGuiToCustomerUserConnectorBridge implements CustomerUserConnectorGuiToCustomerUserConnectorInterface
{
    /**
     * @var \Spryker\Zed\CustomerUserConnector\Business\CustomerUserConnectorFacadeInterface
     */
    protected $customerUserConnectorFacade;

    /**
     * @param \Spryker\Zed\CustomerUserConnector\Business\CustomerUserConnectorFacadeInterface $customerUserConnectorFacade
     */
    public function __construct($customerUserConnectorFacade)
    {
        $this->customerUserConnectorFacade = $customerUserConnectorFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerUserConnectionUpdateTransfer $customerUserConnectionUpdateTransfer
     *
     * @return bool
     */
    public function updateCustomerUserConnection(CustomerUserConnectionUpdateTransfer $customerUserConnectionUpdateTransfer)
    {
        return $this->customerUserConnectorFacade->updateCustomerUserConnection($customerUserConnectionUpdateTransfer);
    }
}
