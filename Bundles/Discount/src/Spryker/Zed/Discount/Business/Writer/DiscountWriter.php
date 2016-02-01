<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\DiscountTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;

class DiscountWriter extends AbstractWriter
{

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    public function create(DiscountTransfer $discountTransfer)
    {
        $discountEntity = new SpyDiscount();
        $discountEntity->fromArray($discountTransfer->toArray());
        $discountEntity->save();

        return $discountEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    public function update(DiscountTransfer $discountTransfer)
    {
        $queryContainer = $this->getQueryContainer();
        $discountEntity = $queryContainer->queryDiscount()->findPk($discountTransfer->getIdDiscount());
        $discountEntity->fromArray($discountTransfer->toArray());
        $discountEntity->save();

        return $discountEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    public function save(DiscountTransfer $discountTransfer)
    {
        if ($discountTransfer->getIdDiscount() > 0) {
            return $this->update($discountTransfer);
        }

        return $this->create($discountTransfer);
    }

}
