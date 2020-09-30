<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;
use Generated\Shared\Transfer\RestSalesOrderConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\RestSalesOrderConfiguredBundleTransfer;

class OrderItemMapper implements OrderItemMapperInterface
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
        if (!$itemTransfer->getSalesOrderConfiguredBundle() || !$itemTransfer->getSalesOrderConfiguredBundleItem()) {
            return $restOrderItemsAttributesTransfer;
        }

        $restSalesOrderConfiguredBundleTransfer = (new RestSalesOrderConfiguredBundleTransfer())
            ->fromArray($itemTransfer->getSalesOrderConfiguredBundle()->toArray(), true);

        $restSalesOrderConfiguredBundleTransfer = $this->copyTranslatedTemplateName($itemTransfer, $restSalesOrderConfiguredBundleTransfer);

        $restSalesOrderConfiguredBundleItemTransfer = (new RestSalesOrderConfiguredBundleItemTransfer())
            ->fromArray($itemTransfer->getSalesOrderConfiguredBundleItem()->toArray(), true);

        $restOrderItemsAttributesTransfer
            ->setSalesOrderConfiguredBundle($restSalesOrderConfiguredBundleTransfer)
            ->setSalesOrderConfiguredBundleItem($restSalesOrderConfiguredBundleItemTransfer);

        return $restOrderItemsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RestSalesOrderConfiguredBundleTransfer $restSalesOrderConfiguredBundleTransfer
     *
     * @return \Generated\Shared\Transfer\RestSalesOrderConfiguredBundleTransfer
     */
    protected function copyTranslatedTemplateName(
        ItemTransfer $itemTransfer,
        RestSalesOrderConfiguredBundleTransfer $restSalesOrderConfiguredBundleTransfer
    ): RestSalesOrderConfiguredBundleTransfer {
        if (!$itemTransfer->getSalesOrderConfiguredBundle()->getTranslations()->offsetExists(0)) {
            return $restSalesOrderConfiguredBundleTransfer;
        }

        return $restSalesOrderConfiguredBundleTransfer->setName(
            $itemTransfer->getSalesOrderConfiguredBundle()->getTranslations()->offsetGet(0)->getName()
        );
    }
}
