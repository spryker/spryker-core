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
use Generated\Shared\Transfer\RestItemProductOptionsAttributesTransfer;
use Generated\Shared\Transfer\RestItemsAttributesTransfer;
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
     * @param \Generated\Shared\Transfer\RestItemsAttributesTransfer $restItemsAttributesTransfer
     * @param string[] $translations
     *
     * @return \Generated\Shared\Transfer\RestItemsAttributesTransfer
     */
    public function mapItemTransferToRestOrderItemsAttributesTransfer(
        ItemTransfer $itemTransfer,
        RestItemsAttributesTransfer $restItemsAttributesTransfer,
        array $translations
    ): RestItemsAttributesTransfer {
        $restCartItemProductOptionsAttributesTransfers = [];
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $restCartItemProductOptionsAttributesTransfers[] = $this->createRestItemProductOptionsAttributesTransfer(
                $productOptionTransfer,
                $translations
            );
        }

        $restItemsAttributesTransfer->setSelectedOptions(new ArrayObject($restCartItemProductOptionsAttributesTransfers));

        return $restItemsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     * @param array $translations
     *
     * @return \Generated\Shared\Transfer\RestItemProductOptionsAttributesTransfer
     */
    protected function createRestItemProductOptionsAttributesTransfer(
        ProductOptionTransfer $productOptionTransfer,
        array $translations
    ): RestItemProductOptionsAttributesTransfer {
        return (new RestItemProductOptionsAttributesTransfer())
            ->setSku($productOptionTransfer->getSku())
            ->setOptionGroupName($translations[$productOptionTransfer->getGroupName()])
            ->setOptionName($translations[$productOptionTransfer->getValue()])
            ->setPrice($productOptionTransfer->getSumPrice());
    }
}
