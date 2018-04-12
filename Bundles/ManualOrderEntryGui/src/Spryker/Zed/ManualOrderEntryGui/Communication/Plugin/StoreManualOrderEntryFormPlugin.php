<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Store\StoreType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class StoreManualOrderEntryFormPlugin extends AbstractPlugin implements ManualOrderEntryFormPluginInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return StoreType::TYPE_NAME;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(Request $request, QuoteTransfer $quoteTransfer): FormInterface
    {
        return $this->getFactory()->createStoreForm($request, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handleData(QuoteTransfer $quoteTransfer, &$form, Request $request): QuoteTransfer
    {
        $quoteTransfer = $this->getFactory()
            ->createStoreFormHandler()
            ->handle($quoteTransfer, $form, $request);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isFormPreFilled(QuoteTransfer $quoteTransfer): bool
    {
        if ($quoteTransfer->getStore() !== null
            && $quoteTransfer->getCurrency() !== null
        ) {
            return $quoteTransfer->getStore()->getName() && $quoteTransfer->getCurrency()->getCode();
        }

        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isFormSkipped(Request $request, QuoteTransfer $quoteTransfer): bool
    {
        return false;
    }
}
