<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationsRestApi\Business\Mapper;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Spryker\Zed\ProductConfigurationsRestApi\Business\Comparator\ItemComparatorInterface;

class ProductConfigurationMapper implements ProductConfigurationMapperInterface
{
    /**
     * @var \Spryker\Zed\ProductConfigurationsRestApi\Business\Comparator\ItemComparatorInterface
     */
    protected $itemComparator;

    /**
     * @param \Spryker\Zed\ProductConfigurationsRestApi\Business\Comparator\ItemComparatorInterface $itemComparator
     */
    public function __construct(ItemComparatorInterface $itemComparator)
    {
        $this->itemComparator = $itemComparator;
    }

    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function mapCartItemRequestTransferToPersistentCartChangeTransfer(
        CartItemRequestTransfer $cartItemRequestTransfer,
        PersistentCartChangeTransfer $persistentCartChangeTransfer
    ): PersistentCartChangeTransfer {
        $productConfigurationInstanceTransfer = $cartItemRequestTransfer->getProductConfigurationInstance();
        if (!$productConfigurationInstanceTransfer) {
            return $persistentCartChangeTransfer;
        }

        foreach ($persistentCartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->itemComparator->isSameItem($itemTransfer, $cartItemRequestTransfer)) {
                continue;
            }

            $itemTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
        }

        return $persistentCartChangeTransfer;
    }
}
