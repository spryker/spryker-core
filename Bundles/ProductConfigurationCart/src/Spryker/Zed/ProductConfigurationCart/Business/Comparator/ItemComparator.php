<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationCart\Business\Comparator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Zed\ProductConfigurationCart\Business\Exception\TransferPropertyNotFoundException;
use Spryker\Zed\ProductConfigurationCart\Dependency\Service\ProductConfigurationCartToProductConfigurationServiceInterface;
use Spryker\Zed\ProductConfigurationCart\ProductConfigurationCartConfig;

class ItemComparator implements ItemComparatorInterface
{
    /**
     * @var \Spryker\Zed\ProductConfigurationCart\Dependency\Service\ProductConfigurationCartToProductConfigurationServiceInterface
     */
    protected $productConfigurationService;

    /**
     * @var \Spryker\Zed\ProductConfigurationCart\ProductConfigurationCartConfig
     */
    protected $productConfigurationCart;

    /**
     * @param \Spryker\Zed\ProductConfigurationCart\Dependency\Service\ProductConfigurationCartToProductConfigurationServiceInterface $productConfigurationService
     * @param \Spryker\Zed\ProductConfigurationCart\ProductConfigurationCartConfig $productConfigurationCart
     */
    public function __construct(
        ProductConfigurationCartToProductConfigurationServiceInterface $productConfigurationService,
        ProductConfigurationCartConfig $productConfigurationCart
    ) {
        $this->productConfigurationService = $productConfigurationService;
        $this->productConfigurationCart = $productConfigurationCart;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemInCartTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @throws \Spryker\Zed\ProductConfigurationCart\Business\Exception\TransferPropertyNotFoundException
     *
     * @return bool
     */
    public function isSameItem(
        ItemTransfer $itemInCartTransfer,
        ItemTransfer $itemTransfer
    ): bool {
        $fields = $this->productConfigurationCart->getItemFieldsForIsSameItemComparison();

        foreach ($fields as $fieldName) {
            if (!$itemTransfer->offsetExists($fieldName)) {
                throw new TransferPropertyNotFoundException(
                    sprintf(
                        'The property "%s" can\'t be found in ItemTransfer.',
                        $fieldName
                    )
                );
            }

            if ($itemInCartTransfer[$fieldName] !== $itemTransfer[$fieldName]) {
                return false;
            }
        }

        return $this->isSameProductConfigurationItem($itemInCartTransfer, $itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemInCartTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isSameProductConfigurationItem(ItemTransfer $itemInCartTransfer, ItemTransfer $itemTransfer): bool
    {
        $itemInCartProductConfigurationInstanceTransfer = $itemInCartTransfer->getProductConfigurationInstance();
        $itemProductConfigurationInstanceTransfer = $itemTransfer->getProductConfigurationInstance();

        return ($itemInCartProductConfigurationInstanceTransfer === null && $itemProductConfigurationInstanceTransfer === null)
            || $this->isProductConfigurationInstanceHashEquals(
                $itemInCartProductConfigurationInstanceTransfer,
                $itemProductConfigurationInstanceTransfer
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null $itemInCartProductConfigurationInstanceTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null $itemProductConfigurationInstanceTransfer
     *
     * @return bool
     */
    protected function isProductConfigurationInstanceHashEquals(
        ?ProductConfigurationInstanceTransfer $itemInCartProductConfigurationInstanceTransfer,
        ?ProductConfigurationInstanceTransfer $itemProductConfigurationInstanceTransfer
    ): bool {
        if ($itemInCartProductConfigurationInstanceTransfer === null || $itemProductConfigurationInstanceTransfer === null) {
            return false;
        }

        return $this->productConfigurationService->getProductConfigurationInstanceHash($itemInCartProductConfigurationInstanceTransfer)
            === $this->productConfigurationService->getProductConfigurationInstanceHash($itemProductConfigurationInstanceTransfer);
    }
}
