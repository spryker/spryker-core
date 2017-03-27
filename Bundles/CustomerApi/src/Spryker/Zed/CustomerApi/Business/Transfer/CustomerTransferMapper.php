<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business\Transfer;

use Generated\Shared\Transfer\CustomerApiTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiInterface;

class CustomerTransferMapper implements CustomerTransferMapperInterface
{

    /**
     * @var \Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiInterface
     */
    protected $apiQueryContainer;

    /**
     * @param \Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiInterface $apiQueryContainer
     */
    public function __construct(CustomerApiToApiInterface $apiQueryContainer)
    {
        $this->apiQueryContainer = $apiQueryContainer;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return \Generated\Shared\Transfer\CustomerApiTransfer
     */
    public function convertCustomer(SpyCustomer $customerEntity)
    {
        $customerTransfer = new CustomerApiTransfer();
        $data = $customerEntity->toArray();

        $customerTransfer->fromArray($data, true);

        return $customerTransfer;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer[]|\Propel\Runtime\Collection\ObjectCollection $customerEntityCollection
     *
     * @return \Generated\Shared\Transfer\CustomerApiTransfer[]
     */
    public function convertCustomerCollection(ObjectCollection $customerEntityCollection)
    {
        $transferList = [];
        foreach ($customerEntityCollection as $customerEntity) {
            $transferList[] = $this->convertCustomer($customerEntity);
        }

        return $transferList;
    }

}
