<?php

namespace SprykerFeature\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\VoucherPoolCategoryTransfer;
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
     * @param VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     *
     * @return SpyDiscountVoucherPoolCategory
     * @throws PropelException
     */
    public function create(VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer)
    {
        $discountVoucherPoolCategoryEntity = $this->locator->discount()->entitySpyDiscountVoucherPoolCategory();
        $discountVoucherPoolCategoryEntity->fromArray($discountVoucherPoolCategoryTransfer->toArray());
        $discountVoucherPoolCategoryEntity->save();

        return $discountVoucherPoolCategoryEntity;
    }

    /**
     * @param VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     * @return array|mixed|SpyDiscountVoucherPoolCategory
     * @throws PropelException
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
}
