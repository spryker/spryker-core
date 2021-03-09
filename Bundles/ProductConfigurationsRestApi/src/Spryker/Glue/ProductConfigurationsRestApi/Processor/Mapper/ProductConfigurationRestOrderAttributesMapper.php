<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;
use Generated\Shared\Transfer\RestSalesOrderItemProductConfigurationInstanceAttributesTransfer;

class ProductConfigurationRestOrderAttributesMapper implements ProductConfigurationRestOrderAttributesMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer
     */
    public function mapItemTransferToRestOrderItemsAttributesTransfer(
        ItemTransfer $itemTransfer,
        RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
    ): RestOrderItemsAttributesTransfer {
        $salesOrderItemConfigurationTransfer = $itemTransfer->getSalesOrderItemConfiguration();
        if (!$salesOrderItemConfigurationTransfer) {
            return $restOrderItemsAttributesTransfer;
        }

        $restSalesOrderItemProductConfigurationInstanceAttributesTransfer = (new RestSalesOrderItemProductConfigurationInstanceAttributesTransfer())
            ->fromArray($salesOrderItemConfigurationTransfer->toArray(), true);

        return $restOrderItemsAttributesTransfer->setSalesOrderItemConfiguration($restSalesOrderItemProductConfigurationInstanceAttributesTransfer);
    }
}
