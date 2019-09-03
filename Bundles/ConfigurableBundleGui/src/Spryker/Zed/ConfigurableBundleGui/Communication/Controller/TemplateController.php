<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Controller;

use ArrayObject;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ConfigurableBundleGui\Business\ConfigurableBundleGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundleGui\Communication\ConfigurableBundleGuiCommunicationFactory getFactory()
 */
class TemplateController extends AbstractController
{
    protected const ROUTE_TEMPLATES_LIST = '/configurable-bundle-gui/';
    protected const ROUTE_EDIT_TEMPLATE = '/configurable-bundle-gui/template/edit';
    protected const URL_PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE = 'id-configurable-bundle-template';

    protected const ERORR_MESSAGE_TEMPLATE_NOT_FOUND = 'Configurable bundle template with id "%id%" was not found';
    protected const ERORR_MESSAGE_PARAM_ID = '%id%';

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
    public function executeCreateAction(Request $request)
    {
        $formDataProvider = $this->getFactory()->createConfigurableBundleTemplateFormDataProvider();

        $form = $this->getFactory()
            ->getConfigurableBundleTemplateForm(
                $formDataProvider->getData(),
                $formDataProvider->getOptions()
            )->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $configurableBundleTemplateResponseTransfer = $this->getFactory()
                ->getConfigurableBundleFacade()
                ->createConfigurableBundleTemplate($form->getData());

            if ($configurableBundleTemplateResponseTransfer->getIsSuccessful()) {
                $idConfigurableBundleTemplate = $configurableBundleTemplateResponseTransfer
                    ->getConfigurableBundleTemplate()
                    ->getIdConfigurableBundleTemplate();

                $redirectUrl = Url::generate(static::ROUTE_EDIT_TEMPLATE, [
                    static::URL_PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE => $idConfigurableBundleTemplate,
                ]);

                return $this->redirectResponse($redirectUrl);
            }

            $this->handleErrors($configurableBundleTemplateResponseTransfer->getMessages());
        }

        return [
            'tabs' => $this->getFactory()->createConfigurableBundleTemplateCreateTabs()->createView(),
            'form' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function executeEditAction(Request $request)
    {
        $idConfigurableBundleTemplate = $this->castId(
            $request->query->get(static::URL_PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE)
        );

        $formDataProvider = $this->getFactory()->createConfigurableBundleTemplateFormDataProvider();
        $configurableBundleTemplateTransfer = $formDataProvider->getData($idConfigurableBundleTemplate);

        if (!$configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()) {
            $this->addErrorMessage(static::ERORR_MESSAGE_TEMPLATE_NOT_FOUND, [
                static::ERORR_MESSAGE_PARAM_ID => $idConfigurableBundleTemplate,
            ]);

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        $configurableBundleTemplateSlotTable = $this->getFactory()->createConfigurableBundleTemplateSlotTable();

        $form = $this->getFactory()
            ->getConfigurableBundleTemplateForm(
                $configurableBundleTemplateTransfer,
                $formDataProvider->getOptions()
            )->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $configurableBundleTemplateResponseTransfer = $this->getFactory()
                ->getConfigurableBundleFacade()
                ->updateConfigurableBundleTemplate($form->getData());

            if ($configurableBundleTemplateResponseTransfer->getIsSuccessful()) {
                $redirectUrl = Url::generate(static::ROUTE_EDIT_TEMPLATE, [
                    static::URL_PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE => $idConfigurableBundleTemplate,
                ]);

                return $this->redirectResponse($redirectUrl);
            }
        }

        return [
            'tabs' => $this->getFactory()->createConfigurableBundleTemplateEditTabs()->createView(),
            'form' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale(),
            'slotTable' => $configurableBundleTemplateSlotTable->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function slotTableAction(): JsonResponse
    {
        $table = $this->getFactory()->createConfigurableBundleTemplateSlotTable();

        return $this->jsonResponse($table->fetchData());
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
