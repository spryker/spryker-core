<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

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
     * @throws PropelException
     *
     * @return SpyDiscountVoucherPoolCategory
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
     *
     * @throws PropelException
     *
     * @return array|mixed|SpyDiscountVoucherPoolCategory
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
     * @return SpyDiscountVoucherPoolCategory
     */
    public function getOrCreateByName($discountPoolCategoryName)
    {
        $category = $this->getQueryContainer()
            ->queryDiscountVoucherPoolCategory()
            ->findOneByName($discountPoolCategoryName)
        ;

        if (is_null($category)) {
            $categoryTransfer = new VoucherPoolCategoryTransfer();
            $categoryTransfer->setName($discountPoolCategoryName);

            return $this->create($categoryTransfer);
        }

        return $category;
    }

}
