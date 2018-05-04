<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Handler;

use ArrayObject;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCartFacadeInterface;
use Symfony\Component\HttpFoundation\Request;

class VoucherFormHandler implements FormHandlerInterface
{
    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCartFacadeInterface $cartFacade
     */
    public function __construct(
        ManualOrderEntryGuiToCartFacadeInterface $cartFacade
    ) {
        $this->cartFacade = $cartFacade;
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
        $voucherCode = $quoteTransfer->getManualOrder()->getVoucherCode();

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setVoucherCode($voucherCode);

        $quoteTransfer->setVoucherDiscounts(new ArrayObject());
        $quoteTransfer->addVoucherDiscount($discountTransfer);

        if (count($quoteTransfer->getItems())) {
            $quoteTransfer = $this->cartFacade->reloadItems($quoteTransfer);
        }

        return $quoteTransfer;
    }
}
