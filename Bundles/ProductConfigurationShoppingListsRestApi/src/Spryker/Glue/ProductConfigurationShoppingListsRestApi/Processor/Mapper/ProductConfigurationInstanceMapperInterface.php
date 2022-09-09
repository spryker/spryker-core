<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationShoppingListsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\RestShoppingListItemProductConfigurationInstanceAttributesTransfer;

interface ProductConfigurationInstanceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestShoppingListItemProductConfigurationInstanceAttributesTransfer $restShoppingListItemProductConfigurationInstanceAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    public function mapRestShoppingListItemProductConfigurationInstanceAttributesToProductConfigurationInstance(
        RestShoppingListItemProductConfigurationInstanceAttributesTransfer $restShoppingListItemProductConfigurationInstanceAttributesTransfer,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfigurationInstanceTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     * @param \Generated\Shared\Transfer\RestShoppingListItemProductConfigurationInstanceAttributesTransfer $restShoppingListItemProductConfigurationInstanceAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemProductConfigurationInstanceAttributesTransfer
     */
    public function mapProductConfigurationInstanceToRestShoppingListItemProductConfigurationInstanceAttributes(
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer,
        RestShoppingListItemProductConfigurationInstanceAttributesTransfer $restShoppingListItemProductConfigurationInstanceAttributesTransfer
    ): RestShoppingListItemProductConfigurationInstanceAttributesTransfer;
}
