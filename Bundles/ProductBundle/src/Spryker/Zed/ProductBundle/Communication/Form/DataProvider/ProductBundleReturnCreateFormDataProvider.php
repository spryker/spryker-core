<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Form\DataProvider;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;

class ProductBundleReturnCreateFormDataProvider
{
    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Form\ReturnCreateForm::FIELD_RETURN_ITEMS
     */
    public const FIELD_RETURN_ITEMS = 'returnItems';
    public const FIELD_RETURN_BUNDLE_ITEMS = 'returnBundleItems';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Form\ReturnCreateForm::OPTION_RETURN_REASONS
     */
    public const OPTION_RETURN_REASONS = 'option_return_reasons';

    /**
     * @uses \Spryker\Client\ProductBundle\Grouper\ProductBundleGrouper::BUNDLE_ITEMS
     */
    public const BUNDLE_ITEMS = 'bundleItems';

    /**
     * @uses \Spryker\Client\ProductBundle\Grouper\ProductBundleGrouper::BUNDLE_PRODUCT
     */
    public const BUNDLE_PRODUCT = 'bundleProduct';

    /**
     * @param array $returnCreateFormData
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function getData(array $returnCreateFormData, OrderTransfer $orderTransfer): array
    {
        $returnCreateFormData = $this->excludeBundleItems($returnCreateFormData);
        $returnCreateFormData = $this->addReturnBundleItems($returnCreateFormData, $orderTransfer);

        return $returnCreateFormData;
    }

    /**
     * @param array $returnCreateFormData
     *
     * @return array
     */
    protected function excludeBundleItems(array $returnCreateFormData): array
    {
        $filteredReturnItems = [];
        $returnItems = $returnCreateFormData[static::FIELD_RETURN_ITEMS] ?? [];

        foreach ($returnItems as $returnItem) {
            /** @var \Generated\Shared\Transfer\ItemTransfer|null $itemTransfer */
            $itemTransfer = $returnItem[ReturnItemTransfer::ORDER_ITEM] ?? null;

            if (!$itemTransfer) {
                continue;
            }

            if (!$itemTransfer->getProductBundle()) {
                $filteredReturnItems[] = [ReturnItemTransfer::ORDER_ITEM => $itemTransfer];
            }
        }

        $returnCreateFormData[static::FIELD_RETURN_ITEMS] = $filteredReturnItems;

        return $returnCreateFormData;
    }

    /**
     * @param array $returnCreateFormData
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function addReturnBundleItems(array $returnCreateFormData, OrderTransfer $orderTransfer): array
    {
        $returnBundleItems = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $relatedBundleItemIdentifier = $itemTransfer->getRelatedBundleItemIdentifier();
            $productBundle = $itemTransfer->getProductBundle();

            if (!$relatedBundleItemIdentifier || !$productBundle) {
                continue;
            }

            if (!isset($returnBundleItems[$relatedBundleItemIdentifier])) {
                $returnBundleItems[$relatedBundleItemIdentifier] = [
                    static::BUNDLE_PRODUCT => $productBundle,
                    static::BUNDLE_ITEMS => [],
                ];
            }

            $returnBundleItems[$relatedBundleItemIdentifier][static::BUNDLE_ITEMS][] = $itemTransfer;
        }

        $returnCreateFormData[static::FIELD_RETURN_BUNDLE_ITEMS] = $returnBundleItems;

        return $returnCreateFormData;
    }
}
