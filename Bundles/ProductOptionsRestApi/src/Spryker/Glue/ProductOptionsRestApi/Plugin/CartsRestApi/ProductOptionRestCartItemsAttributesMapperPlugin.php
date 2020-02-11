<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Plugin\CartsRestApi;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RestItemsAttributesTransfer;
use Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\RestCartItemsAttributesMapperPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\ProductOptionsRestApi\ProductOptionsRestApiFactory getFactory()
 */
class ProductOptionRestCartItemsAttributesMapperPlugin extends AbstractPlugin implements RestCartItemsAttributesMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps ItemTransfer product options to RestOrderItemsAttributesTransfer selected options.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RestItemsAttributesTransfer $restItemsAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestItemsAttributesTransfer
     */
    public function mapItemTransferToRestItemsAttributesTransfer(
        ItemTransfer $itemTransfer,
        RestItemsAttributesTransfer $restItemsAttributesTransfer,
        string $localeName
    ): RestItemsAttributesTransfer {
        $itemTransfer = $this->getFactory()
            ->createProductOptionTranslator()
            ->translateItemTransfer($itemTransfer, $localeName);

        return $this->getFactory()
            ->createProductOptionMapper()
            ->mapItemTransferToRestItemsAttributesTransfer($itemTransfer, $restItemsAttributesTransfer);
    }
}
