<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business\Transfer;

use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Propel\Runtime\Collection\ObjectCollection;

class CustomerTransferMapper implements CustomerTransferMapperInterface
{

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function convertCustomer(SpyCustomer $customerEntity)
    {
        //TODO filter fields
        $customerTransfer = new CustomerTransfer();

        $customerTransfer->fromArray($customerEntity->toArray(), true);

        return $customerTransfer;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer[]|\Propel\Runtime\Collection\ObjectCollection $customerEntityCollection
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer[]
     */
    public function convertCustomerCollection(ObjectCollection $customerEntityCollection)
    {
        $transferList = [];
        foreach ($customerEntityCollection as $customerEntity) {
            $transferList[] = $this->convertCustomer($customerEntity);
        }

        return $transferList;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer[]|\Propel\Runtime\Collection\ObjectCollection $customerEntityCollection
     *
     * @return array
     */
    public function convertCustomerCollectionToArray(ObjectCollection $customerEntityCollection)
    {
        $transferList = [];
        foreach ($customerEntityCollection as $customerEntity) {
            $customerTransfer = $this->convertCustomer($customerEntity);
            $transferList[] = $customerTransfer->toArray();
        }

        return $transferList;
    }

}
