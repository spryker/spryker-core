<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerRestApiToCustomerClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return null|\Generated\Shared\Transfer\CustomerTransfer
     */
    public function findCustomerById(CustomerTransfer $customerTransfer): ?CustomerTransfer;

    /**
     * @return null|\Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomer(): ?CustomerTransfer;
}
