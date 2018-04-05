<?php

namespace Spryker\Zed\Customer\Business\Customer;

use Generated\Shared\Transfer\CustomerCollectionTransfer;

interface CustomerReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerCollectionTransfer $customerCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function getCustomerCollection(CustomerCollectionTransfer $customerCollectionTransfer): CustomerCollectionTransfer;
}
