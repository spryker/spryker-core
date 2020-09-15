<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RestItemsAttributesTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;

interface ItemMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RestItemsAttributesTransfer $restItemsAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestItemsAttributesTransfer
     */
    public function mapItemToRestItemsAttributes(
        ItemTransfer $itemTransfer,
        RestItemsAttributesTransfer $restItemsAttributesTransfer,
        string $localeName
    ): RestItemsAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer
     */
    public function mapItemToRestOrderItemsAttributes(
        ItemTransfer $itemTransfer,
        RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
    ): RestOrderItemsAttributesTransfer;
}
