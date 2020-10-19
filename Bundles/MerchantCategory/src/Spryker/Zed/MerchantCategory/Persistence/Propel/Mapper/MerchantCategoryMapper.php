<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryTransfer;
use Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategory;

class MerchantCategoryMapper
{
    /**
     * @param \Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategory $merchantCategoryEntity
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function mapMerchantCategoryEntityToCategoryTransfer(
        SpyMerchantCategory $merchantCategoryEntity,
        CategoryTransfer $categoryTransfer
    ): CategoryTransfer {
        $categoryTransfer->fromArray($merchantCategoryEntity->getSpyCategory()->toArray(), true);

        return $categoryTransfer;
    }
}
