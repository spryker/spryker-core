<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DiscountPromotionsRestApi\Plugin\CartCodesRestApi;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\RestDiscountsAttributesTransfer;
use Spryker\Glue\CartCodesRestApiExtension\Dependency\Plugin\DiscountMapperPluginInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\DiscountPromotionsRestApi\DiscountPromotionsRestApiFactory getFactory()
 */
class DiscountPromotionDiscountMapperPlugin extends AbstractController implements DiscountMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps `DiscountPromotion.abstractSku` and `DiscountPromotion.quantity` to `RestDiscountsAttributesTransfer`.
     * - Does nothing if the `DiscountTransfer.discountPromotion` is not set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\RestDiscountsAttributesTransfer $restDiscountsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestDiscountsAttributesTransfer
     */
    public function mapDiscountTransferToRestDiscountsAttributesTransfer(
        DiscountTransfer $discountTransfer,
        RestDiscountsAttributesTransfer $restDiscountsAttributesTransfer
    ): RestDiscountsAttributesTransfer {
        return $this->getFactory()->createPromotionItemMapper()->mapDiscountPromotionToRestDiscountsAttributesTransfer(
            $discountTransfer,
            $restDiscountsAttributesTransfer,
        );
    }
}
