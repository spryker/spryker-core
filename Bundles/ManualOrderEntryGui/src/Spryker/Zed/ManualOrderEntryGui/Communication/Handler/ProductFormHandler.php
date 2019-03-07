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
use Spryker\Service\ManualOrderEntryGui\ManualOrderEntryGuiServiceInterface;
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
     * @var \Spryker\Service\ManualOrderEntryGui\ManualOrderEntryGuiServiceInterface
     */
    protected $service;

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToProductFacadeInterface $productFacade
     * @param \Spryker\Service\ManualOrderEntryGui\ManualOrderEntryGuiServiceInterface $service
     */
    public function __construct(
        ManualOrderEntryGuiToCartFacadeInterface $cartFacade,
        ManualOrderEntryGuiToProductFacadeInterface $productFacade,
        ManualOrderEntryGuiServiceInterface $service
    ) {
        $this->cartFacade = $cartFacade;
        $this->productFacade = $productFacade;
        $this->service = $service;
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

        foreach ($quoteTransfer->getManualOrder()->getProducts() as $newProduct) {
            if ($this->isProductInvalid($newProduct, $addedSkus)) {
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
                $newQuantity = $this->service->round(
                    $items[$itemTransfer->getSku()]->getQuantity() + $itemTransfer->getQuantity()
                );
                $items[$itemTransfer->getSku()]->setQuantity($newQuantity);
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

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $newProduct
     * @param array $addedSkus
     *
     * @return bool
     */
    protected function isProductInvalid(ItemTransfer $newProduct, array $addedSkus): bool
    {
        return $newProduct->getSku() === ''
            || $newProduct->getQuantity() <= 0.0
            || in_array($newProduct->getSku(), $addedSkus)
            || !$this->productFacade->hasProductConcrete($newProduct->getSku());
    }
}
