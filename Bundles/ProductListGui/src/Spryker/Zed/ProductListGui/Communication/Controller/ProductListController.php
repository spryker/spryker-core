<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Controller;

use Generated\Shared\Transfer\ProductListAggregateFormTransfer;
use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Shared\ProductListGui\ProductListGuiConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductListGui\Communication\Form\ProductListProductConcreteRelationFormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 */
class ProductListController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $productListAggregateForm = $this->createProductListAggregateForm($request);
        $productListTransfer = $this->handleProductListAggregateForm(
            $request,
            $productListAggregateForm
        );

        if ($productListTransfer) {
            $this->addSuccessMessage(
                sprintf(ProductListGuiConstants::MESSAGE_PRODUCT_LIST_CREATE_SUCCESS, $productListTransfer->getTitle())
            );

            return $this->redirectResponse(ProductListGuiConstants::REDIRECT_URL_DEFAULT);
        }

        return $this->viewResponse($this->executeCreateAction($productListAggregateForm));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request)
    {
        $productListAggregateForm = $this->createProductListAggregateForm($request);
        $productListTransfer = $this->handleProductListAggregateForm(
            $request,
            $productListAggregateForm
        );

        if ($productListTransfer) {
            $this->addSuccessMessage(
                sprintf(ProductListGuiConstants::MESSAGE_PRODUCT_LIST_UPDATE_SUCCESS, $productListTransfer->getTitle())
            );

            return $this->redirectResponse(ProductListGuiConstants::REDIRECT_URL_DEFAULT);
        }

        return $this->viewResponse($this->executeEditAction($request, $productListAggregateForm));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function deleteAction(Request $request)
    {
        $idProductList = $this->castId($request->query->get(ProductListGuiConstants::URL_PARAM_ID_PRODUCT_LIST));

        return $this->viewResponse([
            'idProductList' => $idProductList,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmAction(Request $request)
    {
        $redirectUrl = $request->query->get(ProductListGuiConstants::URL_PARAM_REDIRECT_URL, ProductListGuiConstants::REDIRECT_URL_DEFAULT);
        $idProductList = $this->castId($request->query->get(ProductListGuiConstants::URL_PARAM_ID_PRODUCT_LIST));
        $productListTransfer = (new ProductListTransfer())->setIdProductList($idProductList);

        $this->getFactory()
            ->getProductListFacade()
            ->deleteProductList($productListTransfer);

        $this->addSuccessMessage(ProductListGuiConstants::MESSAGE_PRODUCT_LIST_DELETE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $productListAggregateForm
     *
     * @return array
     */
    protected function executeCreateAction(FormInterface $productListAggregateForm): array
    {
        $assignedProductConcreteRelationTabs = $this->getFactory()->createAssignedProductConcreteRelationTabs();
        $availableProductConcreteRelationTabs = $this->getFactory()->createAvailableProductConcreteRelationTabs();

        $availableProductConcreteTable = $this->getFactory()->createAvailableProductConcreteTable();
        $assignedProductConcreteTable = $this->getFactory()->createAssignedProductConcreteTable();

        return [
            'productListAggregationTabs' => $this->getFactory()->createProductListAggregationTabs()->createView(),
            'aggregateForm' => $productListAggregateForm->createView(),
            'availableProductConcreteRelationTabs' => $availableProductConcreteRelationTabs->createView(),
            'assignedProductConcreteRelationTabs' => $assignedProductConcreteRelationTabs->createView(),
            'availableProductConcreteTable' => $availableProductConcreteTable->render(),
            'assignedProductConcreteTable' => $assignedProductConcreteTable->render(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $productListAggregateForm
     *
     * @return array
     */
    protected function executeEditAction(Request $request, FormInterface $productListAggregateForm)
    {
        $idProductList = $this->castId($request->get(ProductListGuiConstants::URL_PARAM_ID_PRODUCT_LIST));
        $data = $this::executeCreateAction($productListAggregateForm);
        $data['idProductList'] = $idProductList;

        return $data;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $aggregateForm
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer|null
     */
    protected function handleProductListAggregateForm(Request $request, FormInterface $aggregateForm): ?ProductListTransfer
    {
        $aggregateForm->handleRequest($request);

        if (!$aggregateForm->isSubmitted() || !$aggregateForm->isValid()) {
            return null;
        }

        /** @var \Generated\Shared\Transfer\ProductListAggregateFormTransfer $aggregateFormTransfer */
        $aggregateFormTransfer = $aggregateForm->getData();

        $productListTransfer = $aggregateFormTransfer->getProductList();
        $productListTransfer->setProductListCategoryRelation($aggregateFormTransfer->getProductListCategoryRelation());
        $productListTransfer->setProductListProductConcreteRelation($aggregateFormTransfer->getProductListProductConcreteRelation());
        $productListTransfer->setProductListProductConcreteRelation(
            $this->getProductListProductConcreteRelationFromCsv(
                $productListTransfer->getProductListProductConcreteRelation(),
                $aggregateForm->get(ProductListAggregateFormTransfer::PRODUCT_LIST_PRODUCT_CONCRETE_RELATION)
            )
        );

        return $this->storeProductList($productListTransfer);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function availableProductConcreteTableAction(): JsonResponse
    {
        $availableProductConcreteTable = $this->getFactory()->createAvailableProductConcreteTable();

        return $this->jsonResponse(
            $availableProductConcreteTable->fetchData()
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function assignedProductConcreteTableAction(): JsonResponse
    {
        $assignedProductConcreteTable = $this->getFactory()->createAssignedProductConcreteTable();

        return $this->jsonResponse(
            $assignedProductConcreteTable->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createProductListAggregateForm(Request $request): FormInterface
    {
        $idProductList = $request->query->getInt(ProductListGuiConstants::URL_PARAM_ID_PRODUCT_LIST, null);
        $aggregateFormDataProvider = $this
            ->getFactory()
            ->createProductListAggregateFormDataProvider();

        $aggregateForm = $this
            ->getFactory()
            ->getProductListAggregateForm(
                $aggregateFormDataProvider->getData($idProductList),
                $aggregateFormDataProvider->getOptions()
            );

        return $aggregateForm;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer $productListProductConcreteRelationTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer
     */
    protected function getProductListProductConcreteRelationFromCsv(
        ProductListProductConcreteRelationTransfer $productListProductConcreteRelationTransfer,
        FormInterface $form
    ): ProductListProductConcreteRelationTransfer {

        $productsCsvFile = $form
            ->get(ProductListProductConcreteRelationFormType::FIELD_FILE_UPLOAD)
            ->getData();

        if (!$productsCsvFile) {
            return $productListProductConcreteRelationTransfer;
        }

        $productListProductConcreteRelationTransfer = $this->getFactory()
            ->createProductListImporter()
            ->importFromCsvFile($productsCsvFile, $productListProductConcreteRelationTransfer);

        return $productListProductConcreteRelationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    protected function storeProductList(ProductListTransfer $productListTransfer): ProductListTransfer
    {
        return $this->getFactory()
            ->getProductListFacade()
            ->saveProductList($productListTransfer);
    }
}
