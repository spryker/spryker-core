<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Handler;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToManualOrderEntryFacadeInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderSourceFormHandler implements FormHandlerInterface
{
    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToManualOrderEntryFacadeInterface
     */
    protected $manualOrderEntryFacade;

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToManualOrderEntryFacadeInterface $manualOrderEntryFacade
     */
    public function __construct(
        ManualOrderEntryGuiToManualOrderEntryFacadeInterface $manualOrderEntryFacade
    ) {
        $this->manualOrderEntryFacade = $manualOrderEntryFacade;
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
        $orderSourceTransfer = $this->manualOrderEntryFacade->getOrderSourceById(
            $quoteTransfer->getOrderSource()->getIdOrderSource()
        );
        $quoteTransfer->setOrderSource($orderSourceTransfer);

        return $quoteTransfer;
    }
}
