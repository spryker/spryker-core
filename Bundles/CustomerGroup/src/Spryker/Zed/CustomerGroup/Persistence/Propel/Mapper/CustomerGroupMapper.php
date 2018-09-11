<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CustomerGroupCollectionTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;

class CustomerGroupMapper
{
    /**
     * @param \Generated\Shared\Transfer\SpyCustomerGroupEntityTransfer[] $customerGroupEntities
     *
     * @return \Generated\Shared\Transfer\CustomerGroupCollectionTransfer
     */
    public function mapCustomerGroupEntitiesToCustomerGroupCollectionTransfer(array $customerGroupEntities): CustomerGroupCollectionTransfer
    {
        $customerGroupCollectionTransfer = new CustomerGroupCollectionTransfer();

        foreach ($customerGroupEntities as $customerGroupEntity) {
            $customerGroupTransfer = new CustomerGroupTransfer();
            $customerGroupTransfer->setName($customerGroupEntity->getName());
            $customerGroupTransfer->setDescription($customerGroupEntity->getDescription());
            $customerGroupTransfer->setIdCustomerGroup($customerGroupEntity->getIdCustomerGroup());
            $customerGroupCollectionTransfer->addGroup($customerGroupTransfer);
        }

        return $customerGroupCollectionTransfer;
    }
}
