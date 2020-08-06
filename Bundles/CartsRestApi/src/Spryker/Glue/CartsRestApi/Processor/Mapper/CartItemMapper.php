<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RestCartItemCalculationsTransfer;
use Generated\Shared\Transfer\RestItemsAttributesTransfer;

class CartItemMapper implements CartItemMapperInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\RestCartItemsAttributesMapperPluginInterface[]
     */
    protected $restCartItemsAttributesMapperPlugins;

    /**
     * @param \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\RestCartItemsAttributesMapperPluginInterface[] $restCartItemsAttributesMapperPlugins
     */
    public function __construct(array $restCartItemsAttributesMapperPlugins)
    {
        $this->restCartItemsAttributesMapperPlugins = $restCartItemsAttributesMapperPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestItemsAttributesTransfer
     */
    public function mapItemTransferToRestItemsAttributesTransfer(
        ItemTransfer $itemTransfer,
        string $localeName
    ): RestItemsAttributesTransfer {
        $itemData = $itemTransfer->toArray();

        $restCartItemsAttributesResponseTransfer = (new RestItemsAttributesTransfer())
            ->fromArray($itemData, true);

        $calculationsTransfer = (new RestCartItemCalculationsTransfer())->fromArray($itemData, true);
        $restCartItemsAttributesResponseTransfer->setCalculations($calculationsTransfer);

        foreach ($this->restCartItemsAttributesMapperPlugins as $restOrderItemsAttributesMapperPlugin) {
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
