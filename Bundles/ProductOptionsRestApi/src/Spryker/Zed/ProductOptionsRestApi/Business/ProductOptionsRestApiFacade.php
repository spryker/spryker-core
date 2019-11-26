<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionsRestApi\Business;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOptionsRestApi\Business\ProductOptionsRestApiBusinessFactory getFactory()
 */
class ProductOptionsRestApiFacade extends AbstractFacade implements ProductOptionsRestApiFacadeInterface
{
    /**
     * Specification:
     * - Maps CartItemRequestTransfer product options to PersistentCartChangeTransfer product options.
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
        return $this->getFactory()
            ->createProductOptionMapper()
            ->mapCartItemRequestTransferToPersistentCartChangeTransfer(
                $cartItemRequestTransfer,
                $persistentCartChangeTransfer
            );
    }
}
