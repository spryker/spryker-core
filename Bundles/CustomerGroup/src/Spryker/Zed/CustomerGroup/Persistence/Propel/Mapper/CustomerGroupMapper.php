<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CustomerGroupCollectionTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;
use Orm\Zed\CustomerGroup\Persistence\Map\SpyCustomerGroupTableMap;
use Propel\Runtime\Collection\ArrayCollection;

class CustomerGroupMapper
{
    /**
     * @param \Propel\Runtime\Collection\ArrayCollection $customerGroups
     *
     * @return \Generated\Shared\Transfer\CustomerGroupCollectionTransfer
     */
    public function mapCustomerGroupNamesToCustomerGroupCollectionTransfer(ArrayCollection $customerGroups): CustomerGroupCollectionTransfer
    {
        $customerGroupCollectionTransfer = new CustomerGroupCollectionTransfer();

        foreach ($customerGroups->getData() as $customerGroup) {
            $customerGroupTransfer = new CustomerGroupTransfer();
            $customerGroupTransfer->setName($customerGroup[SpyCustomerGroupTableMap::COL_NAME]);
            $customerGroupTransfer->setDescription($customerGroup[SpyCustomerGroupTableMap::COL_DESCRIPTION]);
            $customerGroupTransfer->setIdCustomerGroup($customerGroup[SpyCustomerGroupTableMap::COL_ID_CUSTOMER_GROUP]);
            $customerGroupCollectionTransfer->addGroup($customerGroupTransfer);
        }

        return $customerGroupCollectionTransfer;
    }
}
