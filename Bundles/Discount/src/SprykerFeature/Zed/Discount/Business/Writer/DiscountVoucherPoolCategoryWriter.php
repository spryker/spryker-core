<?php

namespace SprykerFeature\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\DiscountDiscountVoucherPoolCategoryTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Discount\Persistence\Propel\Base\SpyDiscountVoucherPoolCategory;

class DiscountVoucherPoolCategoryWriter extends AbstractWriter
{
    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @param DiscountDiscountVoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     *
     * @return SpyDiscountVoucherPoolCategory
     * @throws PropelException
     */
    public function create(DiscountDiscountVoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer)
    {
        $discountVoucherPoolCategoryEntity = $this->locator->discount()->entitySpyDiscountVoucherPoolCategory();
        $discountVoucherPoolCategoryEntity->fromArray($discountVoucherPoolCategoryTransfer->toArray());
        $discountVoucherPoolCategoryEntity->save();

        return $discountVoucherPoolCategoryEntity;
    }

    /**
     * @param DiscountDiscountVoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     * @return array|mixed|SpyDiscountVoucherPoolCategory
     * @throws PropelException
     */
    public function update(DiscountDiscountVoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer)
    {
        $queryContainer = $this->getQueryContainer();
        $discountVoucherPoolCategoryEntity = $queryContainer
            ->queryDiscountVoucherPoolCategory()
            ->findPk($discountVoucherPoolCategoryTransfer->getIdDiscountVoucherPoolCategory());
        $discountVoucherPoolCategoryEntity->fromArray($discountVoucherPoolCategoryTransfer->toArray());
        $discountVoucherPoolCategoryEntity->save();

        return $discountVoucherPoolCategoryEntity;
    }
}
