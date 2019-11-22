<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;
use Generated\Shared\Transfer\ProductOptionGroupStorageTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ProductOptionValueStorageTransfer;
use Generated\Shared\Transfer\RestOrderItemProductOptionsTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;
use Generated\Shared\Transfer\RestProductOptionsAttributesTransfer;

class ProductOptionMapper implements ProductOptionMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer
     * @param \Generated\Shared\Transfer\RestProductOptionsAttributesTransfer[] $restProductOptionsAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestProductOptionsAttributesTransfer[]
     */
    public function mapProductAbstractOptionStorageTransferToRestProductOptionsAttributesTransfers(
        ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer,
        array $restProductOptionsAttributesTransfers = []
    ): array {
        foreach ($productAbstractOptionStorageTransfer->getProductOptionGroups() as $productOptionGroupStorageTransfer) {
            foreach ($productOptionGroupStorageTransfer->getProductOptionValues() as $productOptionValueStorageTransfer) {
                $restProductOptionsAttributesTransfers[] = $this->createRestProductOptionsAttributesTransfer(
                    $productOptionGroupStorageTransfer,
                    $productOptionValueStorageTransfer
                );
            }
        }

        return $restProductOptionsAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupStorageTransfer $productOptionGroupStorageTransfer
     * @param \Generated\Shared\Transfer\ProductOptionValueStorageTransfer $productOptionValueStorageTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductOptionsAttributesTransfer
     */
    protected function createRestProductOptionsAttributesTransfer(
        ProductOptionGroupStorageTransfer $productOptionGroupStorageTransfer,
        ProductOptionValueStorageTransfer $productOptionValueStorageTransfer
    ): RestProductOptionsAttributesTransfer {
        return (new RestProductOptionsAttributesTransfer())
            ->fromArray($productOptionValueStorageTransfer->toArray(), true)
            ->setOptionGroupName($productOptionGroupStorageTransfer->getName())
            ->setOptionName($productOptionValueStorageTransfer->getValue());
    }

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
        $restOrderItemsAttributesTransfers = [];
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $restOrderItemsAttributesTransfers[] = $this->mapProductOptionTransferToRestOrderItemProductOptionTransfer(
                $productOptionTransfer,
                new RestOrderItemProductOptionsTransfer()
            );
        }

        $restOrderItemsAttributesTransfer->setProductOptions(new ArrayObject($restOrderItemsAttributesTransfers));

        return $restOrderItemsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     * @param \Generated\Shared\Transfer\RestOrderItemProductOptionsTransfer $restOrderItemProductOptionsTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderItemProductOptionsTransfer
     */
    protected function mapProductOptionTransferToRestOrderItemProductOptionTransfer(
        ProductOptionTransfer $productOptionTransfer,
        RestOrderItemProductOptionsTransfer $restOrderItemProductOptionsTransfer
    ): RestOrderItemProductOptionsTransfer {
        return $restOrderItemProductOptionsTransfer
            ->setSku($productOptionTransfer->getSku())
            ->setOptionGroupName($productOptionTransfer->getGroupName())
            ->setOptionName($productOptionTransfer->getValue())
            ->setPrice($productOptionTransfer->getSumPrice());
    }
}
