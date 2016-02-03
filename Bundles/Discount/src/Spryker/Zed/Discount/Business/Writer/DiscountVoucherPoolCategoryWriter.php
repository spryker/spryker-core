<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\VoucherPoolCategoryTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategory;

class DiscountVoucherPoolCategoryWriter extends AbstractWriter
{

    /**
     * @param \Generated\Shared\Transfer\VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategory
     */
    public function create(VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer)
    {
        $discountVoucherPoolCategoryEntity = new SpyDiscountVoucherPoolCategory();
        $discountVoucherPoolCategoryEntity->fromArray($discountVoucherPoolCategoryTransfer->toArray());
        $discountVoucherPoolCategoryEntity->save();

        return $discountVoucherPoolCategoryEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategory
     */
    public function update(VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer)
    {
        $queryContainer = $this->getQueryContainer();
        $discountVoucherPoolCategoryEntity = $queryContainer
            ->queryDiscountVoucherPoolCategory()
            ->findPk($discountVoucherPoolCategoryTransfer->getIdDiscountVoucherPoolCategory());
        $discountVoucherPoolCategoryEntity->fromArray($discountVoucherPoolCategoryTransfer->toArray());
        $discountVoucherPoolCategoryEntity->save();

        return $discountVoucherPoolCategoryEntity;
    }

    /**
     * @param string $discountPoolCategoryName
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategory
     */
    public function getOrCreateByName($discountPoolCategoryName)
    {
        $category = $this->getQueryContainer()
            ->queryDiscountVoucherPoolCategory()
            ->filterByName($discountPoolCategoryName)
            ->findOneOrCreate();
        $category->save();

        return $category;
    }

}
