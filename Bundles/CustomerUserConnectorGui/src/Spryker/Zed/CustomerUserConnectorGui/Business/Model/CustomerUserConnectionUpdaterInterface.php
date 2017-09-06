<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui\Business\Model;

use Generated\Shared\Transfer\CustomerUserConnectionTransfer;

interface CustomerUserConnectionUpdaterInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerUserConnectionTransfer $customerUserConnectionTransfer
     *
     * @return bool
     */
    public function updateCustomerUserConnection(CustomerUserConnectionTransfer $customerUserConnectionTransfer);

}
