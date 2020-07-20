<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ConfigurableBundleGui\Communication\ConfigurableBundleGuiCommunicationFactory getFactory()
 */
class SlotController extends AbstractController
{
    /**
     * @uses \Spryker\Zed\ProductListGui\Communication\Plugin\ConfigurableBundleGui\ProductConcreteRelationConfigurableBundleTemplateSlotEditSubTabsProviderPlugin::AVAILABLE_PRODUCT_CONCRETE_RELATION_TABS_NAME
     */
    protected const AVAILABLE_PRODUCT_CONCRETE_RELATION_TABS_NAME = 'availableProductConcreteRelationTabs';

    /**
     * @uses \Spryker\Zed\ProductListGui\Communication\Plugin\ConfigurableBundleGui\ProductConcreteRelationConfigurableBundleTemplateSlotEditSubTabsProviderPlugin::ASSIGNED_PRODUCT_CONCRETE_RELATION_TABS_NAME
     */
    protected const ASSIGNED_PRODUCT_CONCRETE_RELATION_TABS_NAME = 'assignedProductConcreteRelationTabs';

    /**
     * @uses \Spryker\Zed\ProductListGui\Communication\Plugin\ConfigurableBundleGui\ProductConcreteRelationConfigurableBundleTemplateSlotEditTablesProviderPlugin::AVAILABLE_PRODUCT_CONCRETE_TABLE_NAME
     */
    protected const AVAILABLE_PRODUCT_CONCRETE_TABLE_NAME = 'availableProductConcreteTable';

    /**
     * @uses \Spryker\Zed\ProductListGui\Communication\Plugin\ConfigurableBundleGui\ProductConcreteRelationConfigurableBundleTemplateSlotEditTablesProviderPlugin::ASSIGNED_PRODUCT_CONCRETE_TABLE_NAME
     */
    protected const ASSIGNED_PRODUCT_CONCRETE_TABLE_NAME = 'assignedProductConcreteTable';

    /**
     * @uses \Spryker\Zed\ProductListGui\Communication\Controller\ProductListAbstractController::URL_PARAM_ID_PRODUCT_LIST
     */
    protected const URL_PARAM_ID_PRODUCT_LIST = 'id-product-list';

    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\SlotController::editAction()
     */
    protected const ROUTE_EDIT_TEMPLATE_SLOT = '/configurable-bundle-gui/slot/edit';

    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\TemplateController::indexAction()
     */
    protected const ROUTE_TEMPLATES_LIST = '/configurable-bundle-gui/template';

    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\TemplateController::editAction()
     */
    protected const ROUTE_EDIT_TEMPLATE = '/configurable-bundle-gui/template/edit';

    protected const ERROR_MESSAGE_SLOT_NOT_FOUND = 'Configurable bundle template slot with id "%id%" was not found.';
    protected const ERROR_MESSAGE_SLOT_CREATE_FAIL = 'Configurable bundle template slot has not been created.';
    protected const ERROR_MESSAGE_SLOT_UPDATE_FAIL = 'Configurable bundle template slot has not been updated.';
    protected const ERROR_MESSAGE_SLOT_DELETE_FAIL = 'Configurable bundle template slot has not been deleted.';

    protected const SUCCESS_MESSAGE_SLOT_CREATED = 'Configurable bundle template slot was successfully created.';
    protected const SUCCESS_MESSAGE_SLOT_UPDATED = 'Configurable bundle template slot was successfully updated.';
    protected const SUCCESS_MESSAGE_SLOT_DELETED = 'Configurable bundle template slot was successfully deleted.';

    protected const SLOTS_TAB_ANCHOR = '#tab-content-slots';

    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE = 'id-configurable-bundle-template';
    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT = 'id-configurable-bundle-template-slot';

    protected const ERROR_MESSAGE_PARAM_ID = '%id%';

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
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function availableProductConcreteTableAction(): JsonResponse
    {
        $availableProductConcreteTable = $productConcreteRelationTables = $this->getFactory()
            ->createProductConcreteRelationTablesProvider()
            ->getTables()['availableProductConcreteTable'];

        return $this->jsonResponse(
            $availableProductConcreteTable->fetchData()
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function assignedProductConcreteTableAction(): JsonResponse
    {
        $assignedProductConcreteTable = $productConcreteRelationTables = $this->getFactory()
            ->createProductConcreteRelationTablesProvider()
            ->getTables()['assignedProductConcreteTable'];

        return $this->jsonResponse(
            $assignedProductConcreteTable->fetchData()
        );
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

        $formDataProvider = $this->getFactory()->createConfigurableBundleTemplateSlotCreateFormDataProvider();

        $form = $this->getFactory()
            ->getConfigurableBundleTemplateSlotCreateForm(
                $formDataProvider->getData($idConfigurableBundleTemplate),
                $formDataProvider->getOptions()
            )->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $configurableBundleTemplateSlotResponseTransfer = $this->getFactory()
                ->getConfigurableBundleFacade()
                ->createConfigurableBundleTemplateSlot($form->getData());

            if ($configurableBundleTemplateSlotResponseTransfer->getIsSuccessful()) {
                $idConfigurableBundleTemplateSlot = $configurableBundleTemplateSlotResponseTransfer
                    ->getConfigurableBundleTemplateSlot()
                    ->getIdConfigurableBundleTemplateSlot();

                $redirectUrl = Url::generate(static::ROUTE_EDIT_TEMPLATE_SLOT, [
                    static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT => $idConfigurableBundleTemplateSlot,
                ]);

                $this->addSuccessMessage(static::SUCCESS_MESSAGE_SLOT_CREATED);

                return $this->redirectResponse($redirectUrl);
            }

            $this->addErrorMessage(static::ERROR_MESSAGE_SLOT_CREATE_FAIL);
        }

        return [
            'tabs' => $this->getFactory()->createConfigurableBundleTemplateSlotCreateTabs()->createView(),
            'form' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale(),
            'configurableBundleTemplate' => $this->findConfigurableBundleTemplateById($idConfigurableBundleTemplate),
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

        $formDataProvider = $this->getFactory()->createConfigurableBundleTemplateSlotEditFormDataProvider();
        $configurableBundleTemplateSlotEditFormTransfer = $formDataProvider->getData($idConfigurableBundleTemplateSlot);

        if (!$configurableBundleTemplateSlotEditFormTransfer->getConfigurableBundleTemplateSlot()) {
            $this->addErrorMessage(static::ERROR_MESSAGE_SLOT_NOT_FOUND, [
                static::ERROR_MESSAGE_PARAM_ID => $idConfigurableBundleTemplateSlot,
            ]);

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        $form = $this->getFactory()
            ->getConfigurableBundleTemplateSlotEditForm(
                $configurableBundleTemplateSlotEditFormTransfer,
                $formDataProvider->getOptions()
            )->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $configurableBundleTemplateSlotEditFormTransfer = $this->getFactory()
                ->createConfigurableBundleTemplateSlotEditFormFileUploadHandler()
                ->handleFileUploads($form, $form->getData());

            $configurableBundleTemplateSlotTransfer = $this->mapFormTransferToRegularTransfer($configurableBundleTemplateSlotEditFormTransfer);

            $configurableBundleTemplateSlotResponseTransfer = $this->getFactory()
                ->getConfigurableBundleFacade()
                ->updateConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);

            if ($configurableBundleTemplateSlotResponseTransfer->getIsSuccessful()) {
                $redirectUrl = Url::generate(static::ROUTE_EDIT_TEMPLATE_SLOT, [
                    static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT => $idConfigurableBundleTemplateSlot,
                ]);

                $this->addSuccessMessage(static::SUCCESS_MESSAGE_SLOT_UPDATED);

                return $this->redirectResponse($redirectUrl);
            }

            $this->addErrorMessage(static::ERROR_MESSAGE_SLOT_UPDATE_FAIL);
        }

        $request->query->set(
            static::URL_PARAM_ID_PRODUCT_LIST,
            (string)$configurableBundleTemplateSlotEditFormTransfer->getConfigurableBundleTemplateSlot()->getProductList()->getIdProductList()
        );

        $configurableBundleTemplateTransfer = $this->findConfigurableBundleTemplateById(
            $configurableBundleTemplateSlotEditFormTransfer->getConfigurableBundleTemplateSlot()->getFkConfigurableBundleTemplate()
        );

        $viewData = [
            'tabs' => $this->getFactory()->createConfigurableBundleTemplateSlotEditTabs()->createView(),
            'form' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale(),
            'configurableBundleTemplate' => $configurableBundleTemplateTransfer,
        ];

        $viewData = array_merge($viewData, $this->getProductListManagementTabsAndTables());

        return $viewData;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeDeleteAction(Request $request): RedirectResponse
    {
        $idConfigurableBundleTemplateSlot = $this->castId(
            $request->query->get(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT)
        );

        $configurableBundleTemplateSlotFilterTransfer = $this->createConfigurableBundleTemplateSlotFilter($idConfigurableBundleTemplateSlot);

        $configurableBundleTemplateSlotTransfer = $this->getFactory()
            ->getConfigurableBundleFacade()
            ->getConfigurableBundleTemplateSlot($configurableBundleTemplateSlotFilterTransfer)
            ->getConfigurableBundleTemplateSlot();

        if (!$configurableBundleTemplateSlotTransfer) {
            $this->addErrorMessage(static::ERROR_MESSAGE_SLOT_NOT_FOUND, [
                static::ERROR_MESSAGE_PARAM_ID => $idConfigurableBundleTemplateSlot,
            ]);

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        $redirectUrl = Url::generate(static::ROUTE_EDIT_TEMPLATE, [
                static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateSlotTransfer->getFkConfigurableBundleTemplate(),
            ]) . static::SLOTS_TAB_ANCHOR;

        $form = $this->getFactory()->createDeleteConfigurableBundleSlotForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage('CSRF token is not valid');

            return $this->redirectResponse($redirectUrl);
        }

        $configurableBundleTemplateSlotResponseTransfer = $this->getFactory()
            ->getConfigurableBundleFacade()
            ->deleteConfigurableBundleTemplateSlot($configurableBundleTemplateSlotFilterTransfer);

        if ($configurableBundleTemplateSlotResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::SUCCESS_MESSAGE_SLOT_DELETED);

            return $this->redirectResponse($redirectUrl);
        }

        $this->addErrorMessage(static::ERROR_MESSAGE_SLOT_DELETE_FAIL);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @return array
     */
    protected function getProductListManagementTabsAndTables(): array
    {
        $productConcreteRelationSubTabs = $this->getFactory()->createProductConcreteRelationSubTabsProvider()->getSubTabs();
        $productConcreteRelationTables = $this->getFactory()->createProductConcreteRelationTablesProvider()->getTables();

        $keyAvailableProductConcreteRelationTabs = static::AVAILABLE_PRODUCT_CONCRETE_RELATION_TABS_NAME;
        $keyAssignedProductConcreteRelationTabs = static::ASSIGNED_PRODUCT_CONCRETE_RELATION_TABS_NAME;

        $keyAvailableProductConcreteRelationTable = static::AVAILABLE_PRODUCT_CONCRETE_TABLE_NAME;
        $keyAssignedProductConcreteRelationTable = static::ASSIGNED_PRODUCT_CONCRETE_TABLE_NAME;

        return [
            $keyAvailableProductConcreteRelationTabs => $productConcreteRelationSubTabs[$keyAvailableProductConcreteRelationTabs]->createView(),
            $keyAssignedProductConcreteRelationTabs => $productConcreteRelationSubTabs[$keyAssignedProductConcreteRelationTabs]->createView(),
            $keyAvailableProductConcreteRelationTable => $productConcreteRelationTables[$keyAvailableProductConcreteRelationTable]->render(),
            $keyAssignedProductConcreteRelationTable => $productConcreteRelationTables[$keyAssignedProductConcreteRelationTable]->render(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer $configurableBundleTemplateSlotEditFormTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer
     */
    protected function mapFormTransferToRegularTransfer(
        ConfigurableBundleTemplateSlotEditFormTransfer $configurableBundleTemplateSlotEditFormTransfer
    ): ConfigurableBundleTemplateSlotTransfer {
        $configurableBundleTemplateSlotTransfer = $configurableBundleTemplateSlotEditFormTransfer->getConfigurableBundleTemplateSlot();
        $productListAggregateFormTransfer = $configurableBundleTemplateSlotEditFormTransfer->getProductListAggregateForm();

        $productListTransfer = $configurableBundleTemplateSlotTransfer->getProductList();

        $productListTransfer
            ->setProductListCategoryRelation($productListAggregateFormTransfer->getProductListCategoryRelation())
            ->setProductListProductConcreteRelation($productListAggregateFormTransfer->getProductListProductConcreteRelation());

        $configurableBundleTemplateSlotTransfer->setProductList($productListTransfer);

        return $configurableBundleTemplateSlotTransfer;
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer|null
     */
    protected function findConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): ?ConfigurableBundleTemplateTransfer
    {
        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->setIdConfigurableBundleTemplate($idConfigurableBundleTemplate)
            ->setTranslationLocales(new ArrayObject([$this->getFactory()->getLocaleFacade()->getCurrentLocale()]));

        return $this->getFactory()
            ->getConfigurableBundleFacade()
            ->getConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer)
            ->getConfigurableBundleTemplate();
    }

    /**
     * @param int $idConfigurableBundleTemplateSlot
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer
     */
    protected function createConfigurableBundleTemplateSlotFilter(int $idConfigurableBundleTemplateSlot): ConfigurableBundleTemplateSlotFilterTransfer
    {
        return (new ConfigurableBundleTemplateSlotFilterTransfer())
            ->setIdConfigurableBundleTemplateSlot($idConfigurableBundleTemplateSlot)
            ->setTranslationLocales(new ArrayObject([$this->getFactory()->getLocaleFacade()->getCurrentLocale()]));
    }
}
