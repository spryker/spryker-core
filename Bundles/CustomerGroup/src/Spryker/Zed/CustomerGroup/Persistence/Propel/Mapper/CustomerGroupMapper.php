<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CustomerGroupCollectionTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;
use Orm\Zed\CustomerGroup\Persistence\Map\SpyCustomerGroupTableMap;

class CustomerGroupMapper
{
    /**
     * @param array $customerGroupNames
     *
     * @return \Generated\Shared\Transfer\CustomerGroupCollectionTransfer
     */
    public function mapCustomerGroupNamesToCustomerGroupCollectionTransfer(array $customerGroupNames): CustomerGroupCollectionTransfer
    {
        $customerGroupCollectionTransfer = new CustomerGroupCollectionTransfer();

        foreach ($customerGroupNames as $customerGroupName) {
            $customerGroupTransfer = new CustomerGroupTransfer();
            $customerGroupTransfer->setName($customerGroupName[SpyCustomerGroupTableMap::COL_NAME]);
            $customerGroupTransfer->setDescription($customerGroupName[SpyCustomerGroupTableMap::COL_DESCRIPTION]);
            $customerGroupTransfer->setIdCustomerGroup($customerGroupName[SpyCustomerGroupTableMap::COL_ID_CUSTOMER_GROUP]);
            $customerGroupCollectionTransfer->addGroup($customerGroupTransfer);
        }

        return $customerGroupCollectionTransfer;
    }
}
