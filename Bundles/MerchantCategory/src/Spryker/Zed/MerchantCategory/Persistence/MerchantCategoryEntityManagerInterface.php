<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Persistence;

interface MerchantCategoryEntityManagerInterface
{
    /**
     * @param int $categoryId
     *
     * @return void
     */
    public function deleteAllByFkCategory(int $categoryId): void;
}
