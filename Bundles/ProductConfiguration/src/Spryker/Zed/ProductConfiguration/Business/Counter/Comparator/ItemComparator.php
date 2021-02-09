<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Business\Counter\Comparator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Service\ProductConfiguration\ProductConfigurationServiceInterface;
use Spryker\Zed\ProductConfiguration\Business\Exception\TransferPropertyNotFoundException;
use Spryker\Zed\ProductConfiguration\ProductConfigurationConfig;

class ItemComparator implements ItemComparatorInterface
{
    /**
     * @var \Spryker\Service\ProductConfiguration\ProductConfigurationServiceInterface
     */
    protected $productConfigurationService;

    /**
     * @var \Spryker\Zed\ProductConfiguration\ProductConfigurationConfig
     */
    protected $productConfigurationConfig;

    /**
     * @param \Spryker\Service\ProductConfiguration\ProductConfigurationServiceInterface $productConfigurationService
     * @param \Spryker\Zed\ProductConfiguration\ProductConfigurationConfig $productConfigurationConfig
     */
    public function __construct(
        ProductConfigurationServiceInterface $productConfigurationService,
        ProductConfigurationConfig $productConfigurationConfig
    ) {
        $this->productConfigurationService = $productConfigurationService;
        $this->productConfigurationConfig = $productConfigurationConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemInCartTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @throws \Spryker\Zed\ProductConfiguration\Business\Exception\TransferPropertyNotFoundException
     *
     * @return bool
     */
    public function isSameItem(
        ItemTransfer $itemInCartTransfer,
        ItemTransfer $itemTransfer
    ): bool {
        $fields = $this->productConfigurationConfig->getItemFieldsForIsSameItemComparison();

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
