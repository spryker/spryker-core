<?php

namespace SprykerFeature\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\DiscountTransfer;

class DiscountWriter extends AbstractWriter
{
    /**
     * @var \Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    /**
     * @param DiscountTransfer $discountTransfer
     * @return mixed
     */
    public function create(DiscountTransfer $discountTransfer)
    {
        $discountEntity = $this->locator->discount()->entitySpyDiscount();
        $discountEntity->fromArray($discountTransfer->toArray());
        $discountEntity->save();

        return $discountEntity;
    }

    /**
     * @param DiscountTransfer $discountTransfer
     * @return array|mixed|\SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function update(DiscountTransfer $discountTransfer)
    {
        $queryContainer = $this->getQueryContainer();
        $discountEntity = $queryContainer->queryDiscount()->findPk($discountTransfer->getIdDiscount());
        $discountEntity->fromArray($discountTransfer->toArray());
        $discountEntity->save();

        return $discountEntity;
    }
}
