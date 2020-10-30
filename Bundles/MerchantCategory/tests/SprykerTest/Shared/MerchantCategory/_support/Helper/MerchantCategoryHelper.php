<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\MerchantCategory\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\MerchantCategoryBuilder;
use Generated\Shared\Transfer\MerchantCategoryTransfer;
use Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategory;
use SprykerTest\Zed\Category\Helper\CategoryHelper;
use SprykerTest\Zed\Merchant\Helper\MerchantHelper;

class MerchantCategoryHelper extends Module
{
    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantCategoryTransfer
     */
    public function haveMerchantCategory(array $seedData = []): MerchantCategoryTransfer
    {
        if (!isset($seedData[MerchantCategoryTransfer::FK_CATEGORY])) {
            $categoryTransfer = $this->getCategoryHelper()->createCategory('category-key');

            $seedData[MerchantCategoryTransfer::FK_CATEGORY] = $categoryTransfer->getIdCategory();
        }

        if (!isset($seedData[MerchantCategoryTransfer::FK_MERCHANT])) {
            $merchantTransfer = $this->getMerchantHelper()->haveMerchant();

            $seedData[MerchantCategoryTransfer::FK_MERCHANT] = $merchantTransfer->getIdMerchant();
        }

        return $this->createMerchantCategory($seedData);
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantCategoryTransfer
     */
    protected function createMerchantCategory(array $seedData): MerchantCategoryTransfer
    {
        /** @var \Generated\Shared\Transfer\MerchantCategoryTransfer $merchantCategoryTransfer */
        $merchantCategoryTransfer = (new MerchantCategoryBuilder($seedData))->build();
        $merchantCategoryEntity = new SpyMerchantCategory();

        $merchantCategoryEntity->fromArray($merchantCategoryTransfer->toArray());

        $merchantCategoryEntity->save();

        $merchantCategoryTransfer->fromArray($merchantCategoryEntity->toArray(), true);

        return $merchantCategoryTransfer;
    }

    /**
     * @return \SprykerTest\Zed\Merchant\Helper\MerchantHelper
     */
    protected function getMerchantHelper(): MerchantHelper
    {
        /** @var \SprykerTest\Zed\Merchant\Helper\MerchantHelper $merchantHelper */
        $merchantHelper = $this->getModule('\\' . MerchantHelper::class);

        return $merchantHelper;
    }

    /**
     * @return \SprykerTest\Zed\Category\Helper\CategoryHelper
     */
    protected function getCategoryHelper(): CategoryHelper
    {
        /** @var \SprykerTest\Zed\Category\Helper\CategoryHelper $categoryHelper */
        $categoryHelper = $this->getModule('\\' . CategoryHelper::class);

        return $categoryHelper;
    }
}
