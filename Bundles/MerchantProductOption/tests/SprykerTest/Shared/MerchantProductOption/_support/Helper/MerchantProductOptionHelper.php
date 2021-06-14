<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\MerchantProductOption\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\MerchantProductOptionGroupBuilder;
use Generated\Shared\Transfer\MerchantProductOptionGroupTransfer;
use Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroup;
use SprykerTest\Shared\ProductOption\Helper\ProductOptionGroupDataHelper;

class MerchantProductOptionHelper extends Module
{
    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantProductOptionGroupTransfer
     */
    public function haveMerchantProductOptionGroup(array $seedData): MerchantProductOptionGroupTransfer
    {
        $merchantProductOptionGroupTransfer = (new MerchantProductOptionGroupBuilder($seedData))->build();

        if (empty($seedData[MerchantProductOptionGroupTransfer::FK_PRODUCT_OPTION_GROUP])) {
            $productOptionGroupTransfer = $this->getProductOptionGroupDataHelper()->haveProductOptionGroup($seedData);
            $merchantProductOptionGroupTransfer->setFkProductOptionGroup($productOptionGroupTransfer->getIdProductOptionGroup());
        }

        $merchantProductOptionGroupEntity = new SpyMerchantProductOptionGroup();
        $merchantProductOptionGroupEntity->fromArray($merchantProductOptionGroupTransfer->toArray());
        $merchantProductOptionGroupEntity->save();

        return $merchantProductOptionGroupTransfer->fromArray($merchantProductOptionGroupEntity->toArray(), true);
    }

    /**
     * @return \SprykerTest\Shared\ProductOption\Helper\ProductOptionGroupDataHelper
     */
    protected function getProductOptionGroupDataHelper(): ProductOptionGroupDataHelper
    {
        /** @var \SprykerTest\Shared\ProductOption\Helper\ProductOptionGroupDataHelper $productOptionGroupDataHelper */
        $productOptionGroupDataHelper = $this->getModule('\\' . ProductOptionGroupDataHelper::class);

        return $productOptionGroupDataHelper;
    }
}
