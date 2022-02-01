<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotionsRestApi\Dependency\Facade;

use Generated\Shared\Transfer\DiscountPromotionCollectionTransfer;
use Generated\Shared\Transfer\DiscountPromotionCriteriaTransfer;

class DiscountPromotionsRestApiToDiscountPromotionFacadeBridge implements DiscountPromotionsRestApiToDiscountPromotionFacadeInterface
{
    /**
     * @var \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface
     */
    protected $discountPromotionFacade;

    /**
     * @param \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface $discountPromotionFacade
     */
    public function __construct($discountPromotionFacade)
    {
        $this->discountPromotionFacade = $discountPromotionFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionCriteriaTransfer $discountPromotionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionCollectionTransfer
     */
    public function getDiscountPromotionCollection(DiscountPromotionCriteriaTransfer $discountPromotionCriteriaTransfer): DiscountPromotionCollectionTransfer
    {
        return $this->discountPromotionFacade->getDiscountPromotionCollection($discountPromotionCriteriaTransfer);
    }
}
