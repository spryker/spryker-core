<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductTaxSetsRestApi\Persistence;

interface ProductTaxSetsRestApiEntityManagerInterface
{
    /**
     * @return void
     */
    public function updateTaxSetsWithoutUuid(): void;
}
