<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Persistence\Mapper;

use Generated\Shared\Transfer\CustomerGroupNamesTransfer;

interface CustomerGroupMapperInterface
{
    /**
     * @param array $customerGroupNames
     *
     * @return \Generated\Shared\Transfer\CustomerGroupNamesTransfer
     */
    public function mapCustomerGroupNamesToCustomerGroupNamesTransfer(array $customerGroupNames): CustomerGroupNamesTransfer;
}
