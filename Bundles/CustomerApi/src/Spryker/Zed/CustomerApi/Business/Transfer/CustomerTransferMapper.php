<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business\Transfer;

use Generated\Shared\Transfer\CustomerApiTransfer;
use Propel\Runtime\Collection\ArrayCollection;
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
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CustomerApiTransfer
     */
    public function convertCustomer(array $data)
    {
        $customerApiTransfer = new CustomerApiTransfer();
        $customerApiTransfer->fromArray($data, true);

        return $customerApiTransfer;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer[]|\Propel\Runtime\Collection\ArrayCollection $customerEntityCollection
     *
     * @return \Generated\Shared\Transfer\CustomerApiTransfer[]
     */
    public function convertCustomerCollection(ArrayCollection $customerEntityCollection)
    {
        $transferList = [];
        foreach ($customerEntityCollection as $customerData) {
            $transferList[] = $this->convertCustomer($customerData);
        }

        return $transferList;
    }

}
