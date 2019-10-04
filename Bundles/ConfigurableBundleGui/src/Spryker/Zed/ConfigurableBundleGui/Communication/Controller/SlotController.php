<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Controller;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\ProductListGui\Communication\Plugin\ConfigurableBundleGui\ProductConcreteRelationConfigurableBundleTemplateSlotEditSubTabsProviderPlugin;
use Spryker\Zed\ProductListGui\Communication\Plugin\ConfigurableBundleGui\ProductConcreteRelationConfigurableBundleTemplateSlotEditTablesProviderPlugin;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ConfigurableBundleGui\Business\ConfigurableBundleGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundleGui\Communication\ConfigurableBundleGuiCommunicationFactory getFactory()
 */
class SlotController extends AbstractController
{
    /**
     * @uses \Spryker\Zed\ProductListGui\Communication\Controller\ProductListAbstractController::URL_PARAM_ID_PRODUCT_LIST
     */
    protected const URL_PARAM_ID_PRODUCT_LIST = 'id-product-list';

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
        $formDataProvider = $this->getFactory()->createConfigurableBundleTemplateSlotCreateFormDataProvider();

        $form = $this->getFactory()
            ->getConfigurableBundleTemplateSlotCreateForm(
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

        $formDataProvider = $this->getFactory()->createConfigurableBundleTemplateSlotEditFormDataProvider();
        $configurableBundleTemplateSlotEditFormTransfer = $formDataProvider->getData($idConfigurableBundleTemplateSlot);

        if (!$configurableBundleTemplateSlotEditFormTransfer->getConfigurableBundleTemplateSlot()->getIdConfigurableBundleTemplateSlot()) {
            $this->addErrorMessage(static::ERROR_MESSAGE_TEMPLATE_SLOT_NOT_FOUND, [
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
            /**
             * @var \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer $configurableBundleTemplateSlotEditFormTransfer
             */
            $configurableBundleTemplateSlotEditFormTransfer = $form->getData();

            $configurableBundleTemplateSlotTransfer = $this->mapFormTransferToRegularTransfer($configurableBundleTemplateSlotEditFormTransfer);

            $configurableBundleResponseTransfer = $this->getFactory()
                ->getConfigurableBundleFacade()
                ->updateConfigurableBundleTemplateSlot($configurableBundleTemplateSlotEditFormTransfer->getConfigurableBundleTemplateSlot());

            if ($configurableBundleResponseTransfer->getIsSuccessful()) {
                $redirectUrl = Url::generate(static::ROUTE_EDIT_TEMPLATE_SLOT, [
                    static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT => $idConfigurableBundleTemplateSlot,
                ]);

                return $this->redirectResponse($redirectUrl);
            }
        }

        $this->setIdProductListParamToRequest($request);

        $viewData = [
            'tabs' => $this->getFactory()->createConfigurableBundleTemplateSlotEditTabs()->createView(),
            'form' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale(),
        ];

        $viewData = array_merge($viewData, $this->getProductListManagementTabsAndTables());

        return $viewData;
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
     * @return array
     */
    protected function getProductListManagementTabsAndTables(): array
    {
        $productConcreteRelationSubTabs = $this->getFactory()->createProductConcreteRelationSubTabsProvider()->getSubTabs();
        $productConcreteRelationTables = $this->getFactory()->createProductConcreteRelationTablesProvider()->getTables();

        $keyAvailableProductConcreteRelationTabs = ProductConcreteRelationConfigurableBundleTemplateSlotEditSubTabsProviderPlugin::AVAILABLE_PRODUCT_CONCRETE_RELATION_TABS_NAME;
        $keyAssignedProductConcreteRelationTabs = ProductConcreteRelationConfigurableBundleTemplateSlotEditSubTabsProviderPlugin::ASSIGNED_PRODUCT_CONCRETE_RELATION_TABS_NAME;

        $keyAvailableProductConcreteRelationTable = ProductConcreteRelationConfigurableBundleTemplateSlotEditTablesProviderPlugin::AVAILABLE_PRODUCT_CONCRETE_TABLE_NAME;
        $keyAssignedProductConcreteRelationTable = ProductConcreteRelationConfigurableBundleTemplateSlotEditTablesProviderPlugin::ASSIGNED_PRODUCT_CONCRETE_TABLE_NAME;

        return [
            $keyAvailableProductConcreteRelationTabs => $productConcreteRelationSubTabs[$keyAvailableProductConcreteRelationTabs]->createView(),
            $keyAssignedProductConcreteRelationTabs => $productConcreteRelationSubTabs[$keyAssignedProductConcreteRelationTabs]->createView(),
            $keyAvailableProductConcreteRelationTable => $productConcreteRelationTables[$keyAvailableProductConcreteRelationTable]->render(),
            $keyAssignedProductConcreteRelationTable => $productConcreteRelationTables[$keyAssignedProductConcreteRelationTable]->render(),
        ];
    }

    /**
     * @see \Spryker\Zed\ProductListGui\Communication\Table\AbstractProductConcreteTable::getIdProductList()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function setIdProductListParamToRequest(Request $request): void
    {
        $idProductList = $this->getFactory()
            ->getConfigurableBundleFacade()
            ->getProductListIdByIdConfigurableBundleTemplate(
                $this->castId($request->get(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT))
            );

        $request->query->set(static::URL_PARAM_ID_PRODUCT_LIST, $idProductList);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer $configurableBundleTemplateSlotEditFormTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer
     */
    protected function mapFormTransferToRegularTransfer(ConfigurableBundleTemplateSlotEditFormTransfer $configurableBundleTemplateSlotEditFormTransfer): ConfigurableBundleTemplateSlotTransfer
    {
        $configurableBundleTemplateSlotTransfer = $configurableBundleTemplateSlotEditFormTransfer->getConfigurableBundleTemplateSlot();
        $ProductListAggregateFormTransfer = $configurableBundleTemplateSlotEditFormTransfer->getProductListAggregateForm();

        $productListTransfer = $configurableBundleTemplateSlotTransfer->getProductList();
        $productListTransfer->setProductListCategoryRelation($ProductListAggregateFormTransfer->getProductListCategoryRelation());
        $productListTransfer->setProductListProductConcreteRelation($ProductListAggregateFormTransfer->getProductListProductConcreteRelation());

        return $configurableBundleTemplateSlotTransfer->setProductList($productListTransfer);
    }
}
