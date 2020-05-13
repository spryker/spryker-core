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
    public const FIELD_RETURN_ITEMS = 'returnItems';
    public const FIELD_RETURN_BUNDLE_ITEMS = 'returnBundleItems';

    public const OPTION_RETURN_REASONS = 'option_return_reasons';

    /**
     * @param array $returnCreateFormData
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function expandData(array $returnCreateFormData, OrderTransfer $orderTransfer): array
    {
        $returnCreateFormData = $this->excludeBundleItems($returnCreateFormData);
//        $returnCreateFormData = $this->addBundleItems($returnCreateFormData);

        return $returnCreateFormData;
    }

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
}
