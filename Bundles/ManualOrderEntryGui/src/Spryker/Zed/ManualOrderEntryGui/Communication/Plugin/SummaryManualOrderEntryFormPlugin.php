<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Summary\SummaryType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class SummaryManualOrderEntryFormPlugin extends AbstractPlugin implements ManualOrderEntryFormPluginInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return SummaryType::TYPE_NAME;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $dataTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(Request $request, $dataTransfer = null): FormInterface
    {
        return $this->getFactory()->createSummaryForm($dataTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handleData($dataTransfer, &$form, $request): QuoteTransfer
    {
        return $dataTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $dataTransfer
     *
     * @return bool
     */
    public function isPreFilled($dataTransfer = null): bool
    {
        return false;
    }
}
