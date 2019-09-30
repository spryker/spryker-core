<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Communication\Plugin\CartExtension;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\PreReloadItemsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductList\Business\ProductListFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductList\ProductListConfig getConfig()
 * @method \Spryker\Zed\ProductList\Communication\ProductListCommunicationFactory getFactory()
 */
class RemoveRestrictedItemsPreReloadPlugin extends AbstractPlugin implements PreReloadItemsPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preReloadItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->filterRestrictedItems($quoteTransfer);
    }
}
