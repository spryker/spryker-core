<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business\Transfer;

use Propel\Runtime\Collection\ArrayCollection;

interface CustomerTransferMapperInterface
{

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CustomerApiTransfer
     */
    public function convertCustomer(array $data);

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer[]|\Propel\Runtime\Collection\ArrayCollection $customerEntityCollection
     *
     * @return \Generated\Shared\Transfer\CustomerApiTransfer[]
     */
    public function convertCustomerCollection(ArrayCollection $customerEntityCollection);

}
