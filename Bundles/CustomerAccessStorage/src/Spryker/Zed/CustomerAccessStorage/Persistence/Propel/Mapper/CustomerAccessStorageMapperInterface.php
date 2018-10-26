<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CustomerAccessTransfer;
use Propel\Runtime\Collection\ObjectCollection;

interface CustomerAccessStorageMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $customerAccessEntities
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function fillCustomerAccessTransferFromEntities(ObjectCollection $customerAccessEntities): CustomerAccessTransfer;
}
