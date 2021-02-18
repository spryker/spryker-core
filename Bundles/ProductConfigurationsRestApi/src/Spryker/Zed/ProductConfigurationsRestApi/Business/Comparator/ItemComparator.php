<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationsRestApi\Business\Comparator;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductConfigurationsRestApi\Business\Exception\TransferPropertyNotFoundException;
use Spryker\Zed\ProductConfigurationsRestApi\ProductConfigurationsRestApiConfig;

class ItemComparator implements ItemComparatorInterface
{
    /**
     * @var \Spryker\Zed\ProductConfigurationsRestApi\ProductConfigurationsRestApiConfig
     */
    protected $productConfigurationsRestApiConfig;

    /**
     * @param \Spryker\Zed\ProductConfigurationsRestApi\ProductConfigurationsRestApiConfig $productConfigurationsRestApiConfig
     */
    public function __construct(ProductConfigurationsRestApiConfig $productConfigurationsRestApiConfig)
    {
        $this->productConfigurationsRestApiConfig = $productConfigurationsRestApiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     *
     * @throws \Spryker\Zed\ProductConfigurationsRestApi\Business\Exception\TransferPropertyNotFoundException
     *
     * @return bool
     */
    public function isSameItem(ItemTransfer $itemTransfer, CartItemRequestTransfer $cartItemRequestTransfer): bool
    {
        $fields = $this->productConfigurationsRestApiConfig->getItemFieldsForIsSameItemComparison();

        foreach ($fields as $fieldName) {
            if (!$itemTransfer->offsetExists($fieldName)) {
                throw new TransferPropertyNotFoundException(
                    sprintf('The property "%s" can\'t be found in ItemTransfer.', $fieldName)
                );
            }

            if ($cartItemRequestTransfer[$fieldName] !== $itemTransfer[$fieldName]) {
                return false;
            }
        }

        return true;
    }
}
