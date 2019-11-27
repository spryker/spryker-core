<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Communication\Plugin\CartExtension;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface;
use Spryker\Zed\CartExtension\Dependency\Plugin\TerminationAwareCartPreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductList\Business\ProductListFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductList\ProductListConfig getConfig()
 * @method \Spryker\Zed\ProductList\Communication\ProductListCommunicationFactory getFactory()
 */
class ProductListRestrictedItemsPreCheckPlugin extends AbstractPlugin implements CartPreCheckPluginInterface, TerminationAwareCartPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        return $this->getFacade()->validateItemAddProductListRestrictions($cartChangeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function terminateOnFailure(): bool
    {
        return true;
    }
}
