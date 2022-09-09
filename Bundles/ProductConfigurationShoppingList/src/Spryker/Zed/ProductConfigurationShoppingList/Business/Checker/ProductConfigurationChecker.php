<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationShoppingList\Business\Checker;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductConfigurationFilterTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;
use Spryker\Zed\ProductConfigurationShoppingList\Dependency\Facade\ProductConfigurationShoppingListToProductConfigurationFacadeInterface;

class ProductConfigurationChecker implements ProductConfigurationCheckerInterface
{
    /**
     * @var \Spryker\Zed\ProductConfigurationShoppingList\Dependency\Facade\ProductConfigurationShoppingListToProductConfigurationFacadeInterface
     */
    protected ProductConfigurationShoppingListToProductConfigurationFacadeInterface $productConfigurationFacade;

    /**
     * @param \Spryker\Zed\ProductConfigurationShoppingList\Dependency\Facade\ProductConfigurationShoppingListToProductConfigurationFacadeInterface $productConfigurationFacade
     */
    public function __construct(ProductConfigurationShoppingListToProductConfigurationFacadeInterface $productConfigurationFacade)
    {
        $this->productConfigurationFacade = $productConfigurationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function checkShoppingListItemProductConfiguration(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListPreAddItemCheckResponseTransfer {
        $shoppingListPreAddItemCheckResponseTransfer = new ShoppingListPreAddItemCheckResponseTransfer();

        if (!$shoppingListItemTransfer->getProductConfigurationInstance()) {
            return $shoppingListPreAddItemCheckResponseTransfer->setIsSuccess(true);
        }

        return $shoppingListPreAddItemCheckResponseTransfer
            ->setIsSuccess($this->hasProductConfiguration($shoppingListItemTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return bool
     */
    protected function hasProductConfiguration(ShoppingListItemTransfer $shoppingListItemTransfer): bool
    {
        $productConfigurationFilterTransfer = (new ProductConfigurationFilterTransfer())
            ->addSku($shoppingListItemTransfer->getSkuOrFail())
            ->setFilter((new FilterTransfer())->setLimit(1));

        return $this->productConfigurationFacade
                ->getProductConfigurationCollection($productConfigurationFilterTransfer)
                ->getProductConfigurations()
                ->count() > 0;
    }
}
