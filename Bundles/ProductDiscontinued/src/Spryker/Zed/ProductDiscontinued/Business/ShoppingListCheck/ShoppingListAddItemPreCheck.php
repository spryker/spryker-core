<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business\ShoppingListCheck;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;
use Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface;

class ShoppingListAddItemPreCheck implements ShoppingListAddItemPreCheckInterface
{
    protected const TRANSLATION_PARAMETER_SKU = '%sku%';
    protected const SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_DISCONTINUED = 'shopping_list.pre.check.product_discontinued';

    /**
     * @var \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface
     */
    protected $productDiscontinuedRepository;

    /**
     * @param \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface $productDiscontinuedRepository
     */
    public function __construct(ProductDiscontinuedRepositoryInterface $productDiscontinuedRepository)
    {
        $this->productDiscontinuedRepository = $productDiscontinuedRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function checkShoppingListItemProductIsNotDiscontinued(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListPreAddItemCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = new ShoppingListPreAddItemCheckResponseTransfer();
        $cartPreCheckResponseTransfer->setIsSuccess(true);

        if ($this->isProductDiscontinued($shoppingListItemTransfer->getSku())) {
            $cartPreCheckResponseTransfer->setIsSuccess(false)
                ->addMessage($this->createItemIsDiscontinuedMessageTransfer($shoppingListItemTransfer->getSku()));
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    protected function isProductDiscontinued(string $sku): bool
    {
        return $this->productDiscontinuedRepository->checkIfProductDiscontinuedBySku($sku);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createItemIsDiscontinuedMessageTransfer(string $sku): MessageTransfer
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue(static::SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_DISCONTINUED);
        $messageTransfer->setParameters([
            static::TRANSLATION_PARAMETER_SKU => $sku,
        ]);

        return $messageTransfer;
    }
}
