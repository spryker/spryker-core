<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi\Plugin\CartsRestApi;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RestItemsAttributesTransfer;
use Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\RestCartItemsAttributesMapperPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\ProductConfigurationsRestApi\ProductConfigurationsRestApiFactory getFactory()
 */
class ProductConfigurationRestCartItemsAttributesMapperPlugin extends AbstractPlugin implements RestCartItemsAttributesMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps `ItemTransfer` product configuration to `RestItemsAttributesTransfer`.
     * - Executes `RestCartItemProductConfigurationMapperPluginInterface` plugin stack.
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
        return $this->getFactory()
            ->createProductConfigurationInstanceMapper()
            ->mapItemTransferToRestItemsAttributesTransfer(
                $itemTransfer,
                $restItemsAttributesTransfer
            );
    }
}
