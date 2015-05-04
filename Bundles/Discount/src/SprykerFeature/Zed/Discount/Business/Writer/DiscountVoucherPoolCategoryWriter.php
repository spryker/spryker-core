<?php

namespace SprykerFeature\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\DiscountDiscountVoucherPoolCategoryTransfer;

/**
 * Class DiscountVoucherPoolCategoryManager
 * @package SprykerFeature\Zed\Discount\Business\Model
 */
class DiscountVoucherPoolCategoryWriter extends AbstractWriter
{
    /**
     * @var \Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    /**
     * @param DiscountVoucherPoolCategory $discountVoucherPoolCategoryTransfer
     * @return array|mixed|\SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolCategory
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function create(DiscountVoucherPoolCategory $discountVoucherPoolCategoryTransfer)
    {
        $discountVoucherPoolCategoryEntity = $this->locator->discount()->entitySpyDiscountVoucherPoolCategory();
        $discountVoucherPoolCategoryEntity->fromArray($discountVoucherPoolCategoryTransfer->toArray());
        $discountVoucherPoolCategoryEntity->save();

        return $discountVoucherPoolCategoryEntity;
    }

    /**
     * @param DiscountVoucherPoolCategory $discountVoucherPoolCategoryTransfer
     * @return array|mixed|\SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolCategory
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function update(DiscountVoucherPoolCategory $discountVoucherPoolCategoryTransfer)
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
