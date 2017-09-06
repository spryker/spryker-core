<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\CustomerUserConnectorGui\Business;

use Generated\Shared\Transfer\CustomerUserConnectionTransfer;

interface CustomerUserConnectorGuiFacadeInterface
{

    /**
     * Specification:
     * // TODO: to do
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerUserConnectionTransfer $customerUserConnectionTransfer
     *
     * @return bool
     */
    public function updateCustomerUserConnection(CustomerUserConnectionTransfer $customerUserConnectionTransfer);

}
