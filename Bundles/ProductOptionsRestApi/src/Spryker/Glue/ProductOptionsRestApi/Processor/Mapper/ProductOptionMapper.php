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
use Generated\Shared\Transfer\RestOrderItemProductOptionTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;
use Generated\Shared\Transfer\RestProductOptionAttributesTransfer;

class ProductOptionMapper implements ProductOptionMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer
     * @param string[] $translations
     *
     * @return \Generated\Shared\Transfer\RestProductOptionAttributesTransfer[]
     */
    public function mapProductAbstractOptionStorageTransferToRestProductOptionAttributesTransfers(
        ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer,
        array $translations
    ): array {
        $restProductOptionAttributesTransfers = [];
        foreach ($productAbstractOptionStorageTransfer->getProductOptionGroups() as $productOptionGroupStorageTransfer) {
            foreach ($productOptionGroupStorageTransfer->getProductOptionValues() as $productOptionValueStorageTransfer) {
                $restProductOptionAttributesTransfers[] = $this->createRestProductOptionAttributesTransfer(
                    $productOptionGroupStorageTransfer,
                    $productOptionValueStorageTransfer,
                    $translations
                );
            }
        }

        return $restProductOptionAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupStorageTransfer $productOptionGroupStorageTransfer
     * @param \Generated\Shared\Transfer\ProductOptionValueStorageTransfer $productOptionValueStorageTransfer
     * @param string[] $translations
     *
     * @return \Generated\Shared\Transfer\RestProductOptionAttributesTransfer
     */
    protected function createRestProductOptionAttributesTransfer(
        ProductOptionGroupStorageTransfer $productOptionGroupStorageTransfer,
        ProductOptionValueStorageTransfer $productOptionValueStorageTransfer,
        array $translations
    ): RestProductOptionAttributesTransfer {
        return (new RestProductOptionAttributesTransfer())
            ->setSku($productOptionValueStorageTransfer->getSku())
            ->setOptionGroupName($translations[$productOptionGroupStorageTransfer->getName()])
            ->setOptionName($translations[$productOptionValueStorageTransfer->getValue()])
            ->setPrice($productOptionValueStorageTransfer->getPrice())
            ->setCurrencyIsoCode($productOptionValueStorageTransfer->getCurrencyIsoCode());
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
            $restOrderItemsAttributesTransfers[] = $this->createRestOrderItemProductOptionTransfer(
                $productOptionTransfer
            );
        }

        $restOrderItemsAttributesTransfer->setProductOptions(new ArrayObject($restOrderItemsAttributesTransfers));

        return $restOrderItemsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderItemProductOptionTransfer
     */
    protected function createRestOrderItemProductOptionTransfer(
        ProductOptionTransfer $productOptionTransfer
    ): RestOrderItemProductOptionTransfer {
        return (new RestOrderItemProductOptionTransfer())
            ->setSku($productOptionTransfer->getSku())
            ->setOptionGroupName($productOptionTransfer->getGroupName())
            ->setOptionName($productOptionTransfer->getValue())
            ->setPrice($productOptionTransfer->getSumPrice());
    }
}
