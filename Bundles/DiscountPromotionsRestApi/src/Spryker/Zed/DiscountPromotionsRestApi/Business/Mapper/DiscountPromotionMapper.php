<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotionsRestApi\Business\Mapper;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Spryker\Zed\DiscountPromotionsRestApi\Dependency\Facade\DiscountPromotionsRestApiToDiscountPromotionInterface;

class DiscountPromotionMapper implements DiscountPromotionMapperInterface
{
    /**
     * @var \Spryker\Zed\DiscountPromotionsRestApi\Dependency\Facade\DiscountPromotionsRestApiToDiscountPromotionInterface
     */
    protected $discountPromotionFacade;

    /**
     * @param \Spryker\Zed\DiscountPromotionsRestApi\Dependency\Facade\DiscountPromotionsRestApiToDiscountPromotionInterface $discountPromotionFacade
     */
    public function __construct(DiscountPromotionsRestApiToDiscountPromotionInterface $discountPromotionFacade)
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

        $discountPromotionTransfer = $this->discountPromotionFacade->findDiscountPromotionByUuid(
            $cartItemRequestTransfer->getDiscountPromotionUuid()
        );

        if ($discountPromotionTransfer === null) {
            return $persistentCartChangeTransfer;
        }

        $persistentCartChangeTransfer->getItems()
            ->offsetGet(0)
            ->setIdDiscountPromotion($discountPromotionTransfer->getIdDiscountPromotion());

        return $persistentCartChangeTransfer;
    }
}
