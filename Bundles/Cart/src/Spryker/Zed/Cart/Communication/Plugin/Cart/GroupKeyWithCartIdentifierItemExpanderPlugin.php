<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * - This plugin must be executed after {@link \Spryker\Zed\Cart\Communication\Plugin\SkuGroupKeyPlugin} execution.
 *
 * @method \Spryker\Zed\Cart\Business\CartFacadeInterface getFacade()
 * @method \Spryker\Zed\Cart\Communication\CartCommunicationFactory getFactory()
 * @method \Spryker\Zed\Cart\CartConfig getConfig()
 */
class GroupKeyWithCartIdentifierItemExpanderPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Epxects `CartChangeTransfer.quote.id` to be provided.
     * - Requires `CartChangeTransfer.items.groupKey` to be set.
     * - Expands items group key with hashed cart identifier.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFacade()->expandItemGroupKeysWithCartIdentifier($cartChangeTransfer);
    }
}
