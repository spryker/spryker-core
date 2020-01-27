<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DiscountPromotionsRestApi;

use Codeception\Actor;
use Generated\Shared\Transfer\DiscountPromotionTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\DiscountPromotionsRestApi\Business\DiscountPromotionsRestApiFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class DiscountPromotionsRestApiBusinessTester extends Actor
{
    use _generated\DiscountPromotionsRestApiBusinessTesterActions;

    /**
     * @param string $promotionItemSku
     * @param int $promotionItemQuantity
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function getDiscountPromotionTransfer(string $promotionItemSku, int $promotionItemQuantity): DiscountPromotionTransfer
    {
        $discountGeneralTransfer = $this->haveDiscount();
        $discountPromotionTransfer = $this->createDiscountPromotionTransfer($promotionItemSku, $promotionItemQuantity);

        return $discountPromotionTransfer->setFkDiscount($discountGeneralTransfer->getIdDiscount());
    }

    /**
     * @param string $promotionSku
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    protected function createDiscountPromotionTransfer(string $promotionSku, int $quantity): DiscountPromotionTransfer
    {
        return (new DiscountPromotionTransfer())
            ->setAbstractSku($promotionSku)
            ->setQuantity($quantity);
    }
}
