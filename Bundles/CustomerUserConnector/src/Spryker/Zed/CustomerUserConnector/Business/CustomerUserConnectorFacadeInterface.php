<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnector\Business;

use Generated\Shared\Transfer\CustomerUserConnectionUpdateTransfer;

interface CustomerUserConnectorFacadeInterface
{
    /**
     * Specification:
     * - Assigns provided user in transfer object to the "transfer.idCustomersToAssign" customers.
     * - De-assigns users from "transfer.idCustomersToDeAssign" customers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerUserConnectionUpdateTransfer $customerUserConnectionUpdateTransfer
     *
     * @return bool
     */
    public function updateCustomerUserConnection(CustomerUserConnectionUpdateTransfer $customerUserConnectionUpdateTransfer);
}
