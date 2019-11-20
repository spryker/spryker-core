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
use Generated\Shared\Transfer\RestItemProductOptionsTransfer;
use Generated\Shared\Transfer\RestItemsAttributesTransfer;
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
            ->setSku($productOptionValueStorageTransfer->getSku())
            ->setOptionGroupName($productOptionGroupStorageTransfer->getName())
            ->setOptionName($productOptionValueStorageTransfer->getValue())
            ->setPrice($productOptionValueStorageTransfer->getPrice())
            ->setCurrencyIsoCode($productOptionValueStorageTransfer->getCurrencyIsoCode());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RestItemsAttributesTransfer $restItemsAttributesTransfer
     * @param string[] $translations
     *
     * @return \Generated\Shared\Transfer\RestItemsAttributesTransfer
     */
    public function mapItemTransferToRestOrderItemsAttributesTransfer(
        ItemTransfer $itemTransfer,
        RestItemsAttributesTransfer $restItemsAttributesTransfer
    ): RestItemsAttributesTransfer {
        $restItemProductOptionsTransfers = [];
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $restItemProductOptionsTransfers[] = $this->createRestItemProductOptionsTransfer(
                $productOptionTransfer
            );
        }

        $restItemsAttributesTransfer->setSelectedOptions(new ArrayObject($restItemProductOptionsTransfers));

        return $restItemsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     *
     * @return \Generated\Shared\Transfer\RestItemProductOptionsTransfer
     */
    protected function createRestItemProductOptionsTransfer(
        ProductOptionTransfer $productOptionTransfer
    ): RestItemProductOptionsTransfer {
        return (new RestItemProductOptionsTransfer())
            ->setSku($productOptionTransfer->getSku())
            ->setOptionGroupName($productOptionTransfer->getGroupName())
            ->setOptionName($productOptionTransfer->getValue())
            ->setPrice($productOptionTransfer->getSumPrice());
    }
}
