<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\DiscountPromotion\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Client\CartExtension\Dependency\Plugin\CartChangeRequestExpanderPluginInterface;

class AddDiscountPromotionCartRequestExpandPlugin implements CartChangeRequestExpanderPluginInterface
{
    /**
     * @var string
     */
    public const URL_PARAM_ID_DISCOUNT_PROMOTION = 'idDiscountPromotion';

    /**
     * Specification:
     * - Adds discount promotion id to quote items if it exist in params
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expand(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer
    {
        $idDiscountPromotion = null;
        if (isset($params[static::URL_PARAM_ID_DISCOUNT_PROMOTION])) {
            $idDiscountPromotion = (int)$params[static::URL_PARAM_ID_DISCOUNT_PROMOTION];
        }

        if ($idDiscountPromotion !== null) {
            foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
                $itemTransfer->setIdDiscountPromotion($idDiscountPromotion);
            }
        }

        return $cartChangeTransfer;
    }
}
