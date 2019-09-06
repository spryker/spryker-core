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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ConfigurableBundleGui\Business\ConfigurableBundleGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundleGui\Communication\ConfigurableBundleGuiCommunicationFactory getFactory()
 */
class TemplateController extends AbstractController
{
    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\TemplateController::indexAction()
     */
    protected const ROUTE_TEMPLATES_LIST = '/configurable-bundle-gui/template';

    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\TemplateController::indexAction()
     */
    protected const ROUTE_EDIT_TEMPLATE = '/configurable-bundle-gui/template/edit';    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE = 'id-configurable-bundle-template';

    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE = 'id-configurable-bundle-template';
    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT = 'id-configurable-bundle-template-slot';

    protected const ERROR_MESSAGE_TEMPLATE_NOT_FOUND = 'Configurable bundle template with id "%id%" was not found';
    protected const ERROR_MESSAGE_PARAM_ID = '%id%';

    protected const MESSAGE_TEMPLATE_ACTIVATED = 'Template "%template_name%" was activated.';
    protected const MESSAGE_TEMPLATE_DEACTIVATED = 'Template "%template_name%" was deactivated.';

    protected const MESSAGE_PARAM_TEMPLATE_NAME = '%template_name%';

    /**
     * @return array
     */
    public function indexAction(): array
    {
        $table = $this->getFactory()
            ->createConfigurableBundleTemplateTable();

        return $this->viewResponse([
            'configurableBundleTemplateTable' => $table->render(),
        ]);
    }

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
                    static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE => $idConfigurableBundleTemplate,
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
            $request->query->get(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE)
        );

        $formDataProvider = $this->getFactory()->createConfigurableBundleTemplateFormDataProvider();
        $configurableBundleTemplateTransfer = $formDataProvider->getData($idConfigurableBundleTemplate);

        if (!$configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()) {
            $this->addErrorMessage(static::ERROR_MESSAGE_TEMPLATE_NOT_FOUND, [
                static::ERROR_MESSAGE_PARAM_ID => $idConfigurableBundleTemplate,
            ]);

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        $configurableBundleTemplateSlotTable = $this->getFactory()
            ->createConfigurableBundleTemplateSlotTable($idConfigurableBundleTemplate);
        $configurableBundleTemplateSlotProductsTable = $this->getFactory()
            ->createConfigurableBundleTemplateSlotProductsTable(0);

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
                    static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE => $idConfigurableBundleTemplate,
                ]);

                return $this->redirectResponse($redirectUrl);
            }
        }

        return [
            'tabs' => $this->getFactory()->createConfigurableBundleTemplateEditTabs()->createView(),
            'form' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale(),
            'slotTable' => $configurableBundleTemplateSlotTable->render(),
            'slotProductsTable' => $configurableBundleTemplateSlotProductsTable->render(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function slotTableAction(Request $request): JsonResponse
    {
        $idConfigurableBundleTemplate = $this->castId(
            $request->query->get(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE)
        );

        $table = $this->getFactory()->createConfigurableBundleTemplateSlotTable($idConfigurableBundleTemplate);

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function slotProductsTableAction(Request $request): JsonResponse
    {
        $idConfigurableBundleTemplateSlot = $this->castId(
            $request->get(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT)
        );

        $table = $this->getFactory()->createConfigurableBundleTemplateSlotProductsTable($idConfigurableBundleTemplateSlot);

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request): RedirectResponse
    {
        $idConfigurableBundleTemplate = $this->castId(
            $request->query->get(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE)
        );

        $configurableBundleTemplateTransfer = $this->getFactory()
            ->getConfigurableBundleFacade()
            ->findConfigurableBundleTemplateById($idConfigurableBundleTemplate);

        if (!$configurableBundleTemplateTransfer) {
            $this->addErrorMessage(static::ERROR_MESSAGE_TEMPLATE_NOT_FOUND, [
                static::ERROR_MESSAGE_PARAM_ID => $idConfigurableBundleTemplate,
            ]);

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        $this->getFactory()
            ->getConfigurableBundleFacade()
            ->deleteConfigurableBundleTemplateById($idConfigurableBundleTemplate);

        return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateAction(Request $request): RedirectResponse
    {
        $idConfigurableBundleTemplate = $this->castId(
            $request->query->get(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE)
        );

        $configurableBundleTemplateTransfer = $this->getFactory()
            ->getConfigurableBundleFacade()
            ->findConfigurableBundleTemplateById($idConfigurableBundleTemplate);

        if (!$configurableBundleTemplateTransfer) {
            $this->addErrorMessage(static::ERROR_MESSAGE_TEMPLATE_NOT_FOUND, [
                static::ERROR_MESSAGE_PARAM_ID => $idConfigurableBundleTemplate,
            ]);

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        $configurableBundleTemplateTransfer->setIsActive(false);

        $configurableBundleTemplateResponseTransfer = $this->getFactory()
            ->getConfigurableBundleFacade()
            ->updateConfigurableBundleTemplate($configurableBundleTemplateTransfer);

        if ($configurableBundleTemplateResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_TEMPLATE_DEACTIVATED, [
                static::MESSAGE_PARAM_TEMPLATE_NAME => $this->getFactory()
                    ->getGlossaryFacade()
                    ->translate($configurableBundleTemplateTransfer->getTranslationKey()),
                ]);

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        $this->handleErrors($configurableBundleTemplateResponseTransfer->getMessages());

        return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction(Request $request): RedirectResponse
    {
        $idConfigurableBundleTemplate = $this->castId(
            $request->query->get(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE)
        );

        $configurableBundleTemplateTransfer = $this->getFactory()
            ->getConfigurableBundleFacade()
            ->findConfigurableBundleTemplateById($idConfigurableBundleTemplate);

        if (!$configurableBundleTemplateTransfer) {
            $this->addErrorMessage(static::ERROR_MESSAGE_TEMPLATE_NOT_FOUND, [
                static::ERROR_MESSAGE_PARAM_ID => $idConfigurableBundleTemplate,
            ]);

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        $configurableBundleTemplateTransfer->setIsActive(true);

        $configurableBundleTemplateResponseTransfer = $this->getFactory()
            ->getConfigurableBundleFacade()
            ->updateConfigurableBundleTemplate($configurableBundleTemplateTransfer);

        if ($configurableBundleTemplateResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_TEMPLATE_ACTIVATED, [
                static::MESSAGE_PARAM_TEMPLATE_NAME => $this->getFactory()
                    ->getGlossaryFacade()
                    ->translate($configurableBundleTemplateTransfer->getTranslationKey()),
            ]);

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        $this->handleErrors($configurableBundleTemplateResponseTransfer->getMessages());

        return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmDeleteAction(Request $request)
    {
        $idConfigurableBundleTemplate = $this->castId(
            $request->query->get(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE)
        );

        $configurableBundleTemplateTransfer = $this->getFactory()
            ->getConfigurableBundleFacade()
            ->findConfigurableBundleTemplateById($idConfigurableBundleTemplate);

        if (!$configurableBundleTemplateTransfer) {
            $this->addErrorMessage(static::ERROR_MESSAGE_TEMPLATE_NOT_FOUND, [
                static::ERROR_MESSAGE_PARAM_ID => $idConfigurableBundleTemplate,
            ]);

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        return [
            'configurableBundleTemplateTransfer' => $configurableBundleTemplateTransfer,
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $table = $this->getFactory()
            ->createConfigurableBundleTemplateTable();

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
