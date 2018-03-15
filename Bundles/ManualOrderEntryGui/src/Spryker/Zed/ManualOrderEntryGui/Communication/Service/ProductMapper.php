<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Service;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Cart\Business\CartFacade;

class ProductMapper
{
    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var CartFacade
     */
    protected $cartFacade;

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToProductFacadeInterface $productFacade
     * @param CartFacade $cartFacade
     */
    public function __construct($productFacade, $cartFacade)
    {
        $this->productFacade = $productFacade;
        $this->cartFacade = $cartFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapSkusToItemsTransfer(QuoteTransfer $quoteTransfer)
    {
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setQuote($quoteTransfer);

        foreach($quoteTransfer->getManualOrderProducts() as $manualOrderProduct) {
            if (!strlen($manualOrderProduct->getSku())
                || (int)$manualOrderProduct->getQuantity()<=0
                || !$this->productFacade->hasProductConcrete($manualOrderProduct->getSku())
            ) {
                continue;
            }
//            $productConcreteTransfer = $this->productFacade->getProductConcrete($manualOrderProduct->getSku());

            $itemTransfer = new ItemTransfer();
            $itemTransfer->setSku($manualOrderProduct->getSku())
                ->setQuantity((int)$manualOrderProduct->getQuantity())
            ;

            $cartChangeTransfer->addItem($itemTransfer);

//            $manualOrderProduct->setSku('');
//            $manualOrderProduct->getQuantity(1);
        }
        $quoteTransfer = $this->cartFacade->add($cartChangeTransfer);

        return $quoteTransfer;
    }


}
