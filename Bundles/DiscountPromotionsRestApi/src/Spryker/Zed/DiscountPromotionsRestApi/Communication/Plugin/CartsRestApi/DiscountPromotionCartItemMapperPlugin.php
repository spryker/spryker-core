<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotionsRestApi\Communication\Plugin\CartsRestApi;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\CartItemMapperPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\DiscountPromotionsRestApi\Business\DiscountPromotionsRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\DiscountPromotionsRestApi\DiscountPromotionsRestApiConfig getConfig()
 */
class DiscountPromotionCartItemMapperPlugin extends AbstractPlugin implements CartItemMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves discount promotion id by `CartItemRequestTransfer::$discountPromotionUuid`.
     * - Maps retrieved discount promotion id to the first item of `PersistentCartChangeTransfer::$items`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function mapCartItemRequestTransferToPersistentCartChangeTransfer(
        CartItemRequestTransfer $cartItemRequestTransfer,
        PersistentCartChangeTransfer $persistentCartChangeTransfer
    ): PersistentCartChangeTransfer {
        return $this->getFacade()
            ->mapCartItemRequestTransferToPersistentCartChangeTransfer(
                $cartItemRequestTransfer,
                $persistentCartChangeTransfer,
            );
    }
}
