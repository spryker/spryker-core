<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Controller;

use ArrayObject;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ConfigurableBundleGui\Business\ConfigurableBundleGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundleGui\Communication\ConfigurableBundleGuiCommunicationFactory getFactory()
 */
class SlotController extends AbstractController
{
    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\SlotController::editAction()
     */
    protected const ROUTE_EDIT_TEMPLATE_SLOT = '/configurable-bundle-gui/template/edit';

    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE = 'id-configurable-bundle-template';
    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT = 'id-configurable-bundle-template-slot';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function createAction(Request $request)
    {
        $response = $this->executeCreateAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->viewResponse($response);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function executeCreateAction(Request $request)
    {
        $idConfigurableBundleTemplate = $this->castId(
            $request->get(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE)
        );
        $formDataProvider = $this->getFactory()->createConfigurableBundleTemplateSlotFormDataProvider();

        $form = $this->getFactory()
            ->getConfigurableBundleTemplateSlotForm(
                $formDataProvider->getData($idConfigurableBundleTemplate),
                $formDataProvider->getOptions()
            )->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $configurableBundleResponseTransfer = $this->getFactory()
                ->getConfigurableBundleFacade()
                ->createConfigurableBundleTemplateSlot($form->getData());

            if ($configurableBundleResponseTransfer->getIsSuccessful()) {
                $idConfigurableBundleTemplateSlot = $configurableBundleResponseTransfer
                    ->getConfigurableBundleTemplateSlot()
                    ->getIdConfigurableBundleTemplateSlot();

                $redirectUrl = Url::generate(static::ROUTE_EDIT_TEMPLATE_SLOT, [
                    static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT => $idConfigurableBundleTemplateSlot,
                ]);

                return $this->redirectResponse($redirectUrl);
            }

            $this->handleErrors($configurableBundleResponseTransfer->getMessages());
        }

        return [
            'tabs' => $this->getFactory()->createConfigurableBundleTemplateSlotCreateTabs()->createView(),
            'form' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale(),
            'idConfigurableBundleTemplate' => $idConfigurableBundleTemplate,
        ];
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $messages
     *
     * @return void
     */
    protected function handleErrors(ArrayObject $messages): void
    {
        foreach ($messages as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue(), $messageTransfer->getParameters());
        }
    }
}
