<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Handler;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCartFacadeInterface;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToProductFacadeInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductFormHandler implements FormHandlerInterface
{
    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToProductFacadeInterface $productFacade
     */
    public function __construct(
        ManualOrderEntryGuiToCartFacadeInterface $cartFacade,
        ManualOrderEntryGuiToProductFacadeInterface $productFacade
    ) {
        $this->cartFacade = $cartFacade;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handle(QuoteTransfer $quoteTransfer, &$form, Request $request): QuoteTransfer
    {
        $cartChangeTransfer = new CartChangeTransfer();
        $addedSkus = [];

        foreach ($quoteTransfer->getManualOrderEntry()->getProducts() as $newProduct) {
            if (!strlen($newProduct->getSku())
                || $newProduct->getQuantity() <= 0
                || in_array($newProduct->getSku(), $addedSkus)
                || !$this->productFacade->hasProductConcrete($newProduct->getSku())
            ) {
                continue;
            }

            $addedSkus[] = $newProduct->getSku();
            $itemTransfer = new ItemTransfer();
            $itemTransfer->fromArray($newProduct->toArray());

            $cartChangeTransfer->addItem($itemTransfer);
        }
        if (count($cartChangeTransfer->getItems())) {
            $cartChangeTransfer->setQuote($quoteTransfer);
            $quoteTransfer = $this->cartFacade->add($cartChangeTransfer);
        }

        $quoteTransfer = $this->mergeItemsBySku($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mergeItemsBySku($quoteTransfer): QuoteTransfer
    {
        $items = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (isset($items[$itemTransfer->getSku()])) {
                $items[$itemTransfer->getSku()]->setQuantity(
                    $items[$itemTransfer->getSku()]->getQuantity() + $itemTransfer->getQuantity()
                );
                continue;
            }

            $newItemTransfer = new ItemTransfer();
            $newItemTransfer->setSku($itemTransfer->getSku())
                ->setQuantity($itemTransfer->getQuantity())
                ->setUnitGrossPrice($itemTransfer->getUnitGrossPrice())
                ->setForcedUnitGrossPrice(true);

            $items[$itemTransfer->getSku()] = $newItemTransfer;
        }
        $items = new ArrayObject($items);
        $quoteTransfer->setItems($items);
        $quoteTransfer->setBundleItems(new ArrayObject());
        if (count($items)) {
            $quoteTransfer = $this->cartFacade->reloadItems($quoteTransfer);
        }

        return $quoteTransfer;
    }
}
