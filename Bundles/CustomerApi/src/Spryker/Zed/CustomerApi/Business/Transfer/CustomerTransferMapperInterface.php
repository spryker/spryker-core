<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business\Transfer;

use Orm\Zed\Customer\Persistence\SpyCustomer;
use Propel\Runtime\Collection\ObjectCollection;

interface CustomerTransferMapperInterface
{

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function convertCustomer(SpyCustomer $customerEntity);

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer[]|\Propel\Runtime\Collection\ObjectCollection $customerEntityCollection
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer[]
     */
    public function convertCustomerCollection(ObjectCollection $customerEntityCollection);

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer[]|\Propel\Runtime\Collection\ObjectCollection $customerEntityCollection
     *
     * @return array
     */
    public function convertCustomerCollectionToArray(ObjectCollection $customerEntityCollection);

}
