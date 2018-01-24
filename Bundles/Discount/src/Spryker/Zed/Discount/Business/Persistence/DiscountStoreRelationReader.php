<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Persistence;

use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class DiscountStoreRelationReader implements DiscountStoreRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @var \Spryker\Zed\Discount\Business\Persistence\DiscountStoreRelationHydratorInterface
     */
    protected $discountStoreRelationHydrator;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $discountQueryContainer
     * @param \Spryker\Zed\Discount\Business\Persistence\DiscountStoreRelationHydratorInterface $discountStoreRelationHydrator
     */
    public function __construct(
        DiscountQueryContainerInterface $discountQueryContainer,
        DiscountStoreRelationHydratorInterface $discountStoreRelationHydrator
    ) {
        $this->discountQueryContainer = $discountQueryContainer;
        $this->discountStoreRelationHydrator = $discountStoreRelationHydrator;
    }

    /**
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelation($idDiscount)
    {
        $discountEntity = $this->discountQueryContainer
            ->queryDiscountWithStoresByFkDiscount($idDiscount)
            ->find()
            ->getFirst();

        return $this->discountStoreRelationHydrator->hydrate($discountEntity);
    }
}
