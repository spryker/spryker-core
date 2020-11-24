<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Persistence;

use Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer;

interface MerchantCategoryEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer $merchantCategoryCriteriaTransfer
     *
     * @return void
     */
    public function delete(MerchantCategoryCriteriaTransfer $merchantCategoryCriteriaTransfer): void;
}
