<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\CustomerGroupDiscountConnector\Dependency\Facade;

use Generated\Shared\Transfer\CustomerGroupNamesTransfer;

interface CustomerGroupDiscountConnectorToCustomerGroupFacadeInterface
{
    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupNamesTransfer
     */
    public function findCustomerGroupNamesByIdCustomer($idCustomer): CustomerGroupNamesTransfer;
}
