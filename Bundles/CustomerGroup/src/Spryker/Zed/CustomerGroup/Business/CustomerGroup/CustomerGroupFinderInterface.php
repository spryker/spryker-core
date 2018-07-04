<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Business\CustomerGroup;

use Generated\Shared\Transfer\CustomerGroupsTransfer;

interface CustomerGroupFinderInterface
{
    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupsTransfer
     */
    public function findCustomerGroupsByIdCustomer(int $idCustomer): CustomerGroupsTransfer;
}
