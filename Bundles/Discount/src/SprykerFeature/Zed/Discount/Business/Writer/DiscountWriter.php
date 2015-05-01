<?php

namespace SprykerFeature\Zed\Discount\Business\Writer;

use SprykerFeature\Shared\Discount\Transfer\Discount as DiscountTransfer;

/**
 * Class DiscountManager
 * @package SprykerFeature\Zed\Discount\Business\Model
 */
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
