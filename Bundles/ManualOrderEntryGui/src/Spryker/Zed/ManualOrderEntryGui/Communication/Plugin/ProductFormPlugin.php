<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class ProductFormPlugin extends AbstractFormPlugin implements ManualOrderEntryFormPluginInterface
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
    public function __construct($cartFacade, $productFacade)
    {
        $this->cartFacade = $cartFacade;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $dataTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(Request $request, $dataTransfer = null)
    {
        return $this->getFactory()->createProductsCollectionForm($dataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handleData($quoteTransfer, &$form, $request)
    {
        $cartChangeTransfer = new CartChangeTransfer();
        $skus = [];

        foreach ($quoteTransfer->getManualOrderProducts() as $manualOrderProduct) {
            if (!strlen($manualOrderProduct->getSku())
                || (int)$manualOrderProduct->getQuantity() <= 0
                || in_array($manualOrderProduct->getSku(), $skus)
                || !$this->productFacade->hasProductConcrete($manualOrderProduct->getSku())
            ) {
                continue;
            }

            $skus[] = $manualOrderProduct->getSku();
            $itemTransfer = new ItemTransfer();
            $itemTransfer->setSku($manualOrderProduct->getSku())
                ->setQuantity((int)$manualOrderProduct->getQuantity());

            $cartChangeTransfer->addItem($itemTransfer);
        }
        if (count($cartChangeTransfer->getItems())) {
            $cartChangeTransfer->setQuote($quoteTransfer);
            $quoteTransfer = $this->cartFacade->add($cartChangeTransfer);
        }

        $form = $this->createForm($request, $quoteTransfer);
        $form->setData($quoteTransfer->toArray());

        return $quoteTransfer;
    }
}
