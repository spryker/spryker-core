<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotionsRestApi\Business\Mapper;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\DiscountPromotionConditionsTransfer;
use Generated\Shared\Transfer\DiscountPromotionCriteriaTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Spryker\Zed\DiscountPromotionsRestApi\Dependency\Facade\DiscountPromotionsRestApiToDiscountPromotionFacadeInterface;

class DiscountPromotionMapper implements DiscountPromotionMapperInterface
{
    /**
     * @var \Spryker\Zed\DiscountPromotionsRestApi\Dependency\Facade\DiscountPromotionsRestApiToDiscountPromotionFacadeInterface
     */
    protected $discountPromotionFacade;

    /**
     * @param \Spryker\Zed\DiscountPromotionsRestApi\Dependency\Facade\DiscountPromotionsRestApiToDiscountPromotionFacadeInterface $discountPromotionFacade
     */
    public function __construct(DiscountPromotionsRestApiToDiscountPromotionFacadeInterface $discountPromotionFacade)
    {
        $this->discountPromotionFacade = $discountPromotionFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function mapCartItemRequestTransferToPersistentCartChangeTransfer(
        CartItemRequestTransfer $cartItemRequestTransfer,
        PersistentCartChangeTransfer $persistentCartChangeTransfer
    ): PersistentCartChangeTransfer {
        if ($cartItemRequestTransfer->getDiscountPromotionUuid() === null || !$persistentCartChangeTransfer->getItems()->offsetExists(0)) {
            return $persistentCartChangeTransfer;
        }

        $discountPromotionConditionsTransfer = (new DiscountPromotionConditionsTransfer())
            ->addUuid($cartItemRequestTransfer->getDiscountPromotionUuidOrFail());
        $discountPromotionCriteriaTransfer = (new DiscountPromotionCriteriaTransfer())
            ->setDiscountPromotionConditions($discountPromotionConditionsTransfer);

        $discountPromotionTransfer = $this->discountPromotionFacade
            ->getDiscountPromotionCollection($discountPromotionCriteriaTransfer)
            ->getDiscountPromotions()
            ->getIterator()
            ->current();

        if ($discountPromotionTransfer === null) {
            return $persistentCartChangeTransfer;
        }

        $persistentCartChangeTransfer->getItems()
            ->offsetGet(0)
            ->setIdDiscountPromotion($discountPromotionTransfer->getIdDiscountPromotion());

        return $persistentCartChangeTransfer;
    }
}
