<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Controller;

use Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer;
use Spryker\Zed\ConfigurableBundleGui\Communication\Form\ConfigurableBundleTemplateForm;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ConfigurableBundleGui\Business\ConfigurableBundleGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundleGui\Communication\ConfigurableBundleGuiCommunicationFactory getFactory()
 */
class TemplateController extends AbstractController
{
    protected const ROUTE_EDIT_TEMPLATE = 'configurable-bundle-gui/template/edit';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function createAction(Request $request)
    {
        $form = $this->getFactory()
            ->getConfigurableBundleTemplateForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $configurableBundleTemplateResponseTransfer = $this->getFactory()
                ->getConfigurableBundleFacade()
                ->createConfigurableBundleTemplate($form->getData());

            if ($configurableBundleTemplateResponseTransfer->getIsSuccessful()) {
                return $this->redirectResponse(self::ROUTE_EDIT_TEMPLATE);
            }
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale(),
            'availableLocales' => $form->getConfig()->getOptions()[ConfigurableBundleTemplateForm::OPTION_AVAILABLE_LOCALES],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer $configurableBundleTemplateResponseTransfer
     *
     * @return void
     */
    protected function handleErrors(ConfigurableBundleTemplateResponseTransfer $configurableBundleTemplateResponseTransfer): void
    {
        foreach ($configurableBundleTemplateResponseTransfer->getMessages() as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }
}
