<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Form\Handler;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Spryker\Zed\ProductBundle\Communication\Form\DataProvider\ProductBundleReturnCreateFormDataProvider;
use Spryker\Zed\ProductBundle\Communication\Form\ReturnCreateBundleItemsSubForm;

class ProductBundleReturnCreateFormHandler implements ProductBundleReturnCreateFormHandlerInterface
{
    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Form\DataProvider\ReturnCreateFormDataProvider::CUSTOM_REASON_KEY
     */
    protected const CUSTOM_REASON_KEY = 'custom_reason';

    /**
     * @param array $returnCreateFormData
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    public function handle(array $returnCreateFormData, ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnCreateRequestTransfer
    {
        $returnBundleItemsFormData = $returnCreateFormData[ProductBundleReturnCreateFormDataProvider::FIELD_RETURN_BUNDLE_ITEMS] ?? [];

        foreach ($returnBundleItemsFormData as $returnBundleItemFormData) {
            if (!$this->isReturnBundleItemChecked($returnBundleItemFormData)) {
                continue;
            }

            $returnCreateRequestTransfer = $this->addBundleItemsToRequest(
                $returnBundleItemFormData,
                $returnCreateRequestTransfer
            );
        }

        return $returnCreateRequestTransfer;
    }

    /**
     * @param array $returnBundleItemFormData
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    protected function addBundleItemsToRequest(
        array $returnBundleItemFormData,
        ReturnCreateRequestTransfer $returnCreateRequestTransfer
    ): ReturnCreateRequestTransfer {
        $reason = $this->extractReason($returnBundleItemFormData);
        $itemTransfers = $returnBundleItemFormData[ProductBundleReturnCreateFormDataProvider::BUNDLE_ITEMS] ?? [];

        foreach ($itemTransfers as $itemTransfer) {
            $returnItemTransfer = (new ReturnItemTransfer())
                ->setOrderItem($itemTransfer)
                ->setReason($reason);

            $returnCreateRequestTransfer->addReturnItem($returnItemTransfer);
        }

        return $returnCreateRequestTransfer;
    }

    /**
     * @param array $returnBundleItemFormData
     *
     * @return string|null
     */
    protected function extractReason(array $returnBundleItemFormData): ?string
    {
        if ($returnBundleItemFormData[ReturnItemTransfer::REASON] === static::CUSTOM_REASON_KEY && $returnBundleItemFormData[ReturnCreateBundleItemsSubForm::FIELD_CUSTOM_REASON]) {
            return $returnBundleItemFormData[ReturnCreateBundleItemsSubForm::FIELD_CUSTOM_REASON];
        }

        return $returnBundleItemFormData[ReturnItemTransfer::REASON];
    }

    /**
     * @param array $returnBundleItemFormData
     *
     * @return bool
     */
    protected function isReturnBundleItemChecked(array $returnBundleItemFormData): bool
    {
        return !empty($returnBundleItemFormData[ItemTransfer::IS_RETURNABLE]);
    }
}
