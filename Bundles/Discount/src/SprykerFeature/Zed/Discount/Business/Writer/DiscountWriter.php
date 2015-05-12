<?php

namespace SprykerFeature\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\DiscountDiscountTransfer;

class DiscountWriter extends AbstractWriter
{
    /**
     * @var \Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    /**
     * @param DiscountDiscountTransfer $discountTransfer
     * @return mixed
     */
    public function create(DiscountDiscountTransfer $discountTransfer)
    {
        $discountEntity = $this->locator->discount()->entitySpyDiscount();
        $discountEntity->fromArray($discountTransfer->toArray());
        $discountEntity->save();

        return $discountEntity;
    }

    /**
     * @param DiscountDiscountTransfer $discountTransfer
     * @return array|mixed|\SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function update(DiscountDiscountTransfer $discountTransfer)
    {
        $queryContainer = $this->getQueryContainer();
        $discountEntity = $queryContainer->queryDiscount()->findPk($discountTransfer->getIdDiscount());
        $discountEntity->fromArray($discountTransfer->toArray());
        $discountEntity->save();

        return $discountEntity;
    }
}
