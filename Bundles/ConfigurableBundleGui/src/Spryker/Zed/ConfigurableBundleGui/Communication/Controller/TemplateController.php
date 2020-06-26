<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ConfigurableBundleGui\Communication\ConfigurableBundleGuiCommunicationFactory getFactory()
 */
class TemplateController extends AbstractController
{
    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\TemplateController::indexAction()
     */
    protected const ROUTE_EDIT_TEMPLATE = '/configurable-bundle-gui/template/edit';

    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\TemplateController::indexAction()
     */
    protected const ROUTE_TEMPLATES_LIST = '/configurable-bundle-gui/template';

    protected const ERROR_MESSAGE_TEMPLATE_NOT_FOUND = 'Configurable bundle template with id "%id%" was not found.';
    protected const ERROR_MESSAGE_TEMPLATE_CREATE_FAIL = 'Configurable bundle template has not been created.';
    protected const ERROR_MESSAGE_TEMPLATE_UPDATE_FAIL = 'Configurable bundle template has not been updated.';
    protected const ERROR_MESSAGE_TEMPLATE_DELETE_FAIL = 'Configurable bundle template has not been deleted.';
    protected const ERROR_MESSAGE_TEMPLATE_ACTIVATE_FAIL = 'Template "%template_name%" has not been activated.';
    protected const ERROR_MESSAGE_TEMPLATE_DEACTIVATE_FAIL = 'Template "%template_name%" has not been deactivated.';

    protected const SUCCESS_MESSAGE_TEMPLATE_CREATED = 'Configurable bundle template has been successfully created.';
    protected const SUCCESS_MESSAGE_TEMPLATE_UPDATED = 'Configurable bundle template has been successfully updated.';
    protected const SUCCESS_MESSAGE_TEMPLATE_DELETED = 'Configurable bundle template has been successfully deleted.';
    protected const SUCCESS_MESSAGE_TEMPLATE_ACTIVATED = 'Template "%template_name%" has been activated.';
    protected const SUCCESS_MESSAGE_TEMPLATE_DEACTIVATED = 'Template "%template_name%" has deactivated.';

    protected const MESSAGE_PARAM_TEMPLATE_NAME = '%template_name%';

    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE = 'id-configurable-bundle-template';
    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT = 'id-configurable-bundle-template-slot';

    protected const ERROR_MESSAGE_PARAM_ID = '%id%';

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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request): RedirectResponse
    {
        $response = $this->executeDeleteAction($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function confirmDeleteAction(Request $request)
    {
        $response = $this->executeConfirmDeleteAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->viewResponse($response);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateAction(Request $request): RedirectResponse
    {
        $response = $this->executeDeactivateAction($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction(Request $request): RedirectResponse
    {
        $response = $this->executeActivateAction($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function slotTableAction(Request $request): JsonResponse
    {
        $idConfigurableBundleTemplate = $this->castId(
            $request->get(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE)
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
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $table = $this->getFactory()
            ->createConfigurableBundleTemplateTable();

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function executeCreateAction(Request $request)
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

                $this->addSuccessMessage(static::SUCCESS_MESSAGE_TEMPLATE_CREATED);

                return $this->redirectResponse($redirectUrl);
            }

            $this->addErrorMessage(static::ERROR_MESSAGE_TEMPLATE_CREATE_FAIL);
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
    protected function executeEditAction(Request $request)
    {
        $idConfigurableBundleTemplate = $this->castId(
            $request->get(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE)
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

                $this->addSuccessMessage(static::SUCCESS_MESSAGE_TEMPLATE_UPDATED);

                return $this->redirectResponse($redirectUrl);
            }

            $this->addErrorMessage(static::ERROR_MESSAGE_TEMPLATE_UPDATE_FAIL);
        }

        return [
            'tabs' => $this->getFactory()->createConfigurableBundleTemplateEditTabs()->createView(),
            'form' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale(),
            'slotTable' => $configurableBundleTemplateSlotTable->render(),
            'slotProductsTable' => $configurableBundleTemplateSlotProductsTable->render(),
            'idConfigurableBundleTemplateSlot' => $request->get(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeDeactivateAction(Request $request): RedirectResponse
    {
        $form = $this->getFactory()->createDeactivateConfigurableBundleTemplateForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage('CSRF token is not valid.');

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        $idConfigurableBundleTemplate = $this->castId(
            $request->query->get(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE)
        );

        $configurableBundleTemplateFilterTransfer = $this->createConfigurableBundleTemplateFilter($idConfigurableBundleTemplate);

        $configurableBundleTemplateTransfer = $this->getFactory()
            ->getConfigurableBundleFacade()
            ->getConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer)
            ->getConfigurableBundleTemplate();

        if (!$configurableBundleTemplateTransfer) {
            $this->addErrorMessage(static::ERROR_MESSAGE_TEMPLATE_NOT_FOUND, [
                static::ERROR_MESSAGE_PARAM_ID => $idConfigurableBundleTemplate,
            ]);

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        $configurableBundleTemplateResponseTransfer = $this->getFactory()
            ->getConfigurableBundleFacade()
            ->deactivateConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);

        if ($configurableBundleTemplateResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::SUCCESS_MESSAGE_TEMPLATE_DEACTIVATED, [
                static::MESSAGE_PARAM_TEMPLATE_NAME => $configurableBundleTemplateTransfer
                    ->getTranslations()[0]
                    ->getName(),
            ]);

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        $this->addErrorMessage(static::ERROR_MESSAGE_TEMPLATE_DEACTIVATE_FAIL, [
            static::MESSAGE_PARAM_TEMPLATE_NAME => $configurableBundleTemplateTransfer
                ->getTranslations()[0]
                ->getName(),
        ]);

        return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeActivateAction(Request $request): RedirectResponse
    {
        $form = $this->getFactory()->createActivateConfigurableBundleTemplateForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage('CSRF token is not valid.');

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        $idConfigurableBundleTemplate = $this->castId(
            $request->query->get(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE)
        );

        $configurableBundleTemplateFilterTransfer = $this->createConfigurableBundleTemplateFilter($idConfigurableBundleTemplate);

        $configurableBundleTemplateTransfer = $this->getFactory()
            ->getConfigurableBundleFacade()
            ->getConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer)
            ->getConfigurableBundleTemplate();

        if (!$configurableBundleTemplateTransfer) {
            $this->addErrorMessage(static::ERROR_MESSAGE_TEMPLATE_NOT_FOUND, [
                static::ERROR_MESSAGE_PARAM_ID => $idConfigurableBundleTemplate,
            ]);

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        $configurableBundleTemplateResponseTransfer = $this->getFactory()
            ->getConfigurableBundleFacade()
            ->activateConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);

        if ($configurableBundleTemplateResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::SUCCESS_MESSAGE_TEMPLATE_ACTIVATED, [
                static::MESSAGE_PARAM_TEMPLATE_NAME => $configurableBundleTemplateTransfer
                    ->getTranslations()[0]
                    ->getName(),
            ]);

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        $this->addErrorMessage(static::ERROR_MESSAGE_TEMPLATE_ACTIVATE_FAIL, [
            static::MESSAGE_PARAM_TEMPLATE_NAME => $configurableBundleTemplateTransfer
                ->getTranslations()[0]
                ->getName(),
        ]);

        return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeDeleteAction(Request $request): RedirectResponse
    {
        $deleteForm = $this->getFactory()->createDeleteConfigurableBundleTemplateForm()->handleRequest($request);

        if (!$deleteForm->isSubmitted() || !$deleteForm->isValid()) {
            $this->addErrorMessage('CSRF token is not valid');

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }
        $idConfigurableBundleTemplate = $this->castId(
            $request->query->get(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE)
        );

        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->setIdConfigurableBundleTemplate($idConfigurableBundleTemplate);

        $configurableBundleTemplateTransfer = $this->getFactory()
            ->getConfigurableBundleFacade()
            ->getConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer)
            ->getConfigurableBundleTemplate();

        if (!$configurableBundleTemplateTransfer) {
            $this->addErrorMessage(static::ERROR_MESSAGE_TEMPLATE_NOT_FOUND, [
                static::ERROR_MESSAGE_PARAM_ID => $idConfigurableBundleTemplate,
            ]);

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        $configurableBundleTemplateResponseTransfer = $this->getFactory()
            ->getConfigurableBundleFacade()
            ->deleteConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);

        if ($configurableBundleTemplateResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::SUCCESS_MESSAGE_TEMPLATE_DELETED);

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        $this->addErrorMessage(static::ERROR_MESSAGE_TEMPLATE_DELETE_FAIL);

        return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function executeConfirmDeleteAction(Request $request)
    {
        $idConfigurableBundleTemplate = $this->castId(
            $request->query->get(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE)
        );

        $configurableBundleTemplateFilterTransfer = $this->createConfigurableBundleTemplateFilter($idConfigurableBundleTemplate);

        $configurableBundleTemplateTransfer = $this->getFactory()
            ->getConfigurableBundleFacade()
            ->getConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer)
            ->getConfigurableBundleTemplate();

        if (!$configurableBundleTemplateTransfer) {
            $this->addErrorMessage(static::ERROR_MESSAGE_TEMPLATE_NOT_FOUND, [
                static::ERROR_MESSAGE_PARAM_ID => $idConfigurableBundleTemplate,
            ]);

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        $deleteForm = $this->getFactory()->createDeleteConfigurableBundleTemplateForm();

        return [
            'configurableBundleTemplateTransfer' => $configurableBundleTemplateTransfer,
            'deleteForm' => $deleteForm->createView(),
        ];
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer
     */
    protected function createConfigurableBundleTemplateFilter(int $idConfigurableBundleTemplate): ConfigurableBundleTemplateFilterTransfer
    {
        return (new ConfigurableBundleTemplateFilterTransfer())
            ->setIdConfigurableBundleTemplate($idConfigurableBundleTemplate)
            ->setTranslationLocales(new ArrayObject([$this->getFactory()->getLocaleFacade()->getCurrentLocale()]));
    }
}
