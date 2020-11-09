<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Business\CategorySet;

interface MerchantCategorySetDeleterInterface
{
    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteMerchantCategorySetsByIdCategory(int $idCategory): void;
}
