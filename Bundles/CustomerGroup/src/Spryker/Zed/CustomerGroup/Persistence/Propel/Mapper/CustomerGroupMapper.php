<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CustomerGroupCollectionTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class CustomerGroupMapper implements CustomerGroupMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $customerGroupEntities
     *
     * @return \Generated\Shared\Transfer\CustomerGroupCollectionTransfer
     */
    public function mapCustomerGroupEntitiesToCustomerGroupCollectionTransfer(ObjectCollection $customerGroupEntities): CustomerGroupCollectionTransfer
    {
        $customerGroupCollectionTransfer = new CustomerGroupCollectionTransfer();

        foreach ($customerGroupEntities as $customerGroupEntity) {
            $customerGroupTransfer = new CustomerGroupTransfer();
            $customerGroupTransfer->fromArray($customerGroupEntity->toArray(), true);
            $customerGroupCollectionTransfer->addGroup($customerGroupTransfer);
        }

        return $customerGroupCollectionTransfer;
    }
}
