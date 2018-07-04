<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\DiscountPromotion\Plugin;

use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Spryker\Client\PersistentCartExtension\Dependency\Plugin\PersistentCartChangeExpanderPluginInterface;

class AddDiscountPromotionPersistentCartRequestExpanderPlugin implements PersistentCartChangeExpanderPluginInterface
{
    public const URL_PARAM_ID_DISCOUNT_PROMOTION = 'idDiscountPromotion';

    /**
     * Specification:
     * - Adds discount promotion id to quote items if it exist in params
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function extend(PersistentCartChangeTransfer $cartChangeTransfer, array $params = []): PersistentCartChangeTransfer
    {
        $idDiscountPromotion = $params[static::URL_PARAM_ID_DISCOUNT_PROMOTION] ?? null;
        if (is_numeric($idDiscountPromotion) && $idDiscountPromotion !== 0) {
            foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
                $itemTransfer->setIdDiscountPromotion((int)$idDiscountPromotion);
            }
        }

        return $cartChangeTransfer;
    }
}
