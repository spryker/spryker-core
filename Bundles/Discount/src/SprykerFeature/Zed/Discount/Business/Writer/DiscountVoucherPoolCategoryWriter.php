<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\VoucherPoolCategoryTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolCategory;

class DiscountVoucherPoolCategoryWriter extends AbstractWriter
{

    /**
     * @param VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     *
     * @throws PropelException
     *
     * @return SpyDiscountVoucherPoolCategory
     */
    public function create(VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer)
    {
        $discountVoucherPoolCategoryEntity = new SpyDiscountVoucherPoolCategory();
        $discountVoucherPoolCategoryEntity->fromArray($discountVoucherPoolCategoryTransfer->toArray());
        $discountVoucherPoolCategoryEntity->save();

        return $discountVoucherPoolCategoryEntity;
    }

    /**
     * @param VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     *
     * @throws PropelException
     *
     * @return SpyDiscountVoucherPoolCategory
     */
    public function update(VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer)
    {
        $queryContainer = $this->getQueryContainer();
        $discountVoucherPoolCategoryEntity = $queryContainer
            ->queryDiscountVoucherPoolCategory()
            ->findPk($discountVoucherPoolCategoryTransfer->getIdDiscountVoucherPoolCategory())
        ;
        $discountVoucherPoolCategoryEntity->fromArray($discountVoucherPoolCategoryTransfer->toArray());
        $discountVoucherPoolCategoryEntity->save();

        return $discountVoucherPoolCategoryEntity;
    }

    /**
     * @param string $discountPoolCategoryName
     *
     * @return SpyDiscountVoucherPoolCategory
     */
    public function getOrCreateByName($discountPoolCategoryName)
    {
        $category = $this->getQueryContainer()
            ->queryDiscountVoucherPoolCategory()
            ->filterByName($discountPoolCategoryName)
            ->findOneOrCreate()
        ;
        $category->save();

        return $category;
    }

}
