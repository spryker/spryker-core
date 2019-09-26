<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
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
    protected const ROUTE_EDIT_TEMPLATE_SLOT = '/configurable-bundle-gui/slot/edit';

    protected const ERROR_MESSAGE_TEMPLATE_SLOT_NOT_FOUND = 'Configurable bundle template slot with id "%id%" was not found.';

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
    public function editAction(Request $request)
    {
        $response = $this->executeEditAction($request);

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
    protected function executeCreateAction(Request $request)
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function executeEditAction(Request $request)
    {
        $idConfigurableBundleTemplateSlot = $this->castId(
            $request->get(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT)
        );

        $formDataProvider = $this->getFactory()->createConfigurableBundleTemplateSlotFormDataProvider();
        $configurableBundleTemplateSlotTransfer = $formDataProvider->getData(
            null,
            $idConfigurableBundleTemplateSlot
        );

        if (!$configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot()) {
            $this->addErrorMessage(static::ERROR_MESSAGE_TEMPLATE_SLOT_NOT_FOUND, [
                static::ERROR_MESSAGE_PARAM_ID => $idConfigurableBundleTemplateSlot,
            ]);

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        $form = $this->getFactory()
            ->getConfigurableBundleTemplateSlotForm(
                $configurableBundleTemplateSlotTransfer,
                $formDataProvider->getOptions()
            )->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $configurableBundleResponseTransfer = $this->getFactory()
                ->getConfigurableBundleFacade()
                ->updateConfigurableBundleTemplateSlot($form->getData());

            if ($configurableBundleResponseTransfer->getIsSuccessful()) {
                $redirectUrl = Url::generate(static::ROUTE_EDIT_TEMPLATE_SLOT, [
                    static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT => $idConfigurableBundleTemplateSlot,
                ]);

                return $this->redirectResponse($redirectUrl);
            }
        }

        return [
            'tabs' => $this->getFactory()->createConfigurableBundleTemplateSlotEditTabs()->createView(),
            'form' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale(),
        ];
    }
}
