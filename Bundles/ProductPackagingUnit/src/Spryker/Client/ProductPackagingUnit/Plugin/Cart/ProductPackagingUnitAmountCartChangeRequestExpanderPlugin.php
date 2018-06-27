<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnit\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Client\CartExtension\Dependency\Plugin\CartChangeRequestExpanderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\ProductPackagingUnit\ProductPackagingUnitClientInterface getClient()
 */
class ProductPackagingUnitAmountCartChangeRequestExpanderPlugin extends AbstractPlugin implements CartChangeRequestExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expand(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer
    {
        return $this->getClient()
            ->expandProductPackagingUnitAmountForCartChangeRequest($cartChangeTransfer, $params);
    }
}
