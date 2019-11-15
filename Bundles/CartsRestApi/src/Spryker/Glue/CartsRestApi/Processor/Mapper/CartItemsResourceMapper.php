<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RestCartItemCalculationsTransfer;
use Generated\Shared\Transfer\RestItemsAttributesTransfer;

class CartItemsResourceMapper implements CartItemsResourceMapperInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\RestCartItemsAttributesMapperPluginInterface[]
     */
    protected $restOrderItemsAttributesMapperPlugins;

    /**
     * @param \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\RestCartItemsAttributesMapperPluginInterface[] $restOrderItemsAttributesMapperPlugins
     */
    public function __construct(array $restOrderItemsAttributesMapperPlugins)
    {
        $this->restOrderItemsAttributesMapperPlugins = $restOrderItemsAttributesMapperPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestItemsAttributesTransfer
     */
    public function mapCartItemAttributes(ItemTransfer $itemTransfer, string $localeName): RestItemsAttributesTransfer
    {
        $itemData = $itemTransfer->toArray();

        $restCartItemsAttributesResponseTransfer = (new RestItemsAttributesTransfer())
            ->fromArray($itemData, true);

        $calculationsTransfer = (new RestCartItemCalculationsTransfer())->fromArray($itemData, true);
        $restCartItemsAttributesResponseTransfer->setCalculations($calculationsTransfer);

        foreach ($this->restOrderItemsAttributesMapperPlugins as $restOrderItemsAttributesMapperPlugin) {
            $restCartItemsAttributesResponseTransfer =
                $restOrderItemsAttributesMapperPlugin->mapItemTransferToRestItemsAttributesTransfer(
                    $itemTransfer,
                    $restCartItemsAttributesResponseTransfer,
                    $localeName
                );
        }

        return $restCartItemsAttributesResponseTransfer;
    }
}
