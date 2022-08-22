<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\TableValidationResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class CreateProductAbstractController extends AbstractController
{
    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_SUCCESS = 'Product successfully created!';

    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_ERROR = 'Please resolve all errors.';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_NAME = 'name';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_SKU = 'sku';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_BACK = 'back';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_CONCRETE_PRODUCTS = 'concreteProducts';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_SELECTED_ATTRIBUTES = 'selectedAttributes';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\ProductsController::ID_TABLE_PRODUCT_LIST
     *
     * @var string
     */
    protected const ID_TABLE_PRODUCT_LIST = 'product-list';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductConcreteMapper::FIELD_NAME
     *
     * @var string
     */
    protected const FIELD_NAME = 'name';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductConcreteMapper::FIELD_SKU
     *
     * @var string
     */
    protected const FIELD_SKU = 'sku';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithSingleConcreteForm::FIELD_CONCRETE_NAME
     *
     * @var string
     */
    protected const FIELD_CONCRETE_NAME = 'concreteName';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithSingleConcreteForm::FIELD_CONCRETE_SKU
     *
     * @var string
     */
    protected const FIELD_CONCRETE_SKU = 'concreteSku';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractForm::FIELD_IS_SINGLE_CONCRETE
     *
     * @var string
     */
    protected const FIELD_IS_SINGLE_CONCRETE = 'isSingleConcrete';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $createProductAbstractForm = $this->getFactory()->createCreateProductAbstractForm($request->query->all());
        $createProductAbstractForm->handleRequest($request);
        $responseData = [
            'form' => $this->renderView('@ProductMerchantPortalGui/Partials/create_product_abstract_form.twig', [
                'form' => $createProductAbstractForm->createView(),
                'defaultLocaleCode' => $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getLocaleNameOrFail(),
            ])->getContent(),
        ];

        if (!$createProductAbstractForm->isSubmitted()) {
            return new JsonResponse($responseData);
        }

        if (!$createProductAbstractForm->isValid()) {
            $responseData = $this->addErrorNotification($responseData, static::RESPONSE_NOTIFICATION_MESSAGE_ERROR);

            return new JsonResponse($responseData);
        }

        $formData = $createProductAbstractForm->getData();

        return new RedirectResponse(
            $this->getFactory()
                ->createCreateProductUrlGenerator()
                ->getCreateUrl($formData, (bool)$formData[static::FIELD_IS_SINGLE_CONCRETE]),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createWithSingleConcreteAction(Request $request)
    {
        $abstractProductName = $request->get(static::REQUEST_PARAM_NAME);
        $abstractProductSku = $request->get(static::REQUEST_PARAM_SKU);
        $isSingleConcrete = $request->get(static::FIELD_IS_SINGLE_CONCRETE);

        if ($request->request->get(static::REQUEST_PARAM_BACK)) {
            return new RedirectResponse(
                $this->getFactory()
                    ->createCreateProductUrlGenerator()
                    ->getCreateProductAbstractUrl(
                        $abstractProductSku,
                        $abstractProductName,
                        $isSingleConcrete,
                    ),
            );
        }

        $defaultData = $this->getFactory()
            ->createCreateProductAbstractWithSingleConcreteFormDataProvider()
            ->getDefaultData($request);

        $createProductAbstractWithSingleConcreteForm = $this->getFactory()
            ->createCreateProductAbstractWithSingleConcreteForm($defaultData);
        $createProductAbstractWithSingleConcreteForm->handleRequest($request);

        $formData = $createProductAbstractWithSingleConcreteForm->getData();
        $responseData = [
            'form' => $this->renderView('@ProductMerchantPortalGui/Partials/create_product_abstract_with_single_concrete_form.twig', [
                'form' => $createProductAbstractWithSingleConcreteForm->createView(),
            ])->getContent(),
            'action' => $this->getFactory()->createCreateProductUrlGenerator()->getCreateUrl($formData, true),
        ];

        if (!$createProductAbstractWithSingleConcreteForm->isSubmitted()) {
            return new JsonResponse($responseData);
        }

        if (!$createProductAbstractWithSingleConcreteForm->isValid()) {
            $responseData = $this->addErrorNotification($responseData, static::RESPONSE_NOTIFICATION_MESSAGE_ERROR);

            return new JsonResponse($responseData);
        }

        $productAbstractTransfer = $this->getProductAbstractTransfer($formData);
        $concreteProductTransfers = $this->getProductConcreteTransfers([
            [
                static::FIELD_SKU => $formData[static::FIELD_CONCRETE_SKU],
                static::FIELD_NAME => $formData[static::FIELD_CONCRETE_NAME],
            ],
        ]);

        $this->getFactory()
            ->getProductFacade()
            ->addProduct($productAbstractTransfer, $concreteProductTransfers);

        return $this->getSuccessResponseAndCloseOverlay();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createWithMultiConcreteAction(Request $request)
    {
        $abstractProductName = $request->get(static::REQUEST_PARAM_NAME);
        $abstractProductSku = $request->get(static::REQUEST_PARAM_SKU);
        $abstractProductIsSingleConcrete = $request->get(static::FIELD_IS_SINGLE_CONCRETE);

        if ($request->request->get(static::REQUEST_PARAM_BACK)) {
            return new RedirectResponse(
                $this->getFactory()
                    ->createCreateProductUrlGenerator()
                    ->getCreateProductAbstractUrl(
                        $abstractProductSku,
                        $abstractProductName,
                        $abstractProductIsSingleConcrete,
                    ),
            );
        }

        $concreteProductsJson = (string)$request->request->get(static::REQUEST_PARAM_CONCRETE_PRODUCTS);
        $selectedAttributesJson = $request->request->get(static::REQUEST_PARAM_SELECTED_ATTRIBUTES);

        $productConcreteData = $this->getFactory()
            ->getUtilEncodingService()
            ->decodeJson($concreteProductsJson, true) ?: [];
        $createProductAbstractWithMultiConcreteForm = $this->getFactory()
            ->createCreateProductAbstractWithMultiConcreteForm($request->query->all());
        $createProductAbstractWithMultiConcreteForm->handleRequest($request);

        $productAbstractTransfer = $this->getProductAbstractTransfer(
            $createProductAbstractWithMultiConcreteForm->getData(),
        );

        $superAttributes = $this->getFactory()
            ->createSuperAttributesDataProvider()
            ->getSuperAttributes();

        $formData = $createProductAbstractWithMultiConcreteForm->getData();
        $viewData = [
            'form' => $createProductAbstractWithMultiConcreteForm->createView(),
            'superProductManagementAttributes' => $superAttributes,
            'productAbstract' => $productAbstractTransfer,
            'concreteProductsJson' => $concreteProductsJson,
            'selectedAttributesJson' => $selectedAttributesJson,
        ];

        if (!$request->isMethod(Request::METHOD_POST)) {
            return new JsonResponse(
                $this->createMultiConcreteResponse($viewData, $formData),
            );
        }

        $tableValidationResponseTransfer = $this->getFactory()
            ->createProductConcreteValidator()
            ->validateConcreteProducts($productConcreteData);

        if (!$tableValidationResponseTransfer->getIsSuccessOrFail()) {
            $viewData['errors'] = $this->extractErrors($tableValidationResponseTransfer);

            $responseData = $this->createMultiConcreteResponse($viewData, $formData);
            $responseData = $this->addErrorNotification($responseData, static::RESPONSE_NOTIFICATION_MESSAGE_ERROR);

            return new JsonResponse($responseData);
        }

        $concreteProductTransfers = $this->getProductConcreteTransfers($productConcreteData);

        $this->getFactory()
            ->getProductFacade()
            ->addProduct($productAbstractTransfer, $concreteProductTransfers);

        return $this->getSuccessResponseAndCloseOverlay();
    }

    /**
     * @param array<mixed> $responseData
     * @param string $errorMessage
     *
     * @return array<mixed>
     */
    protected function addErrorNotification(array $responseData, string $errorMessage): array
    {
        $zedUiFormResponseTransfer = $this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addErrorNotification($errorMessage)
            ->createResponse();

        return array_merge($responseData, $zedUiFormResponseTransfer->toArray());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getSuccessResponseAndCloseOverlay(): JsonResponse
    {
        $zedUiFormResponseTransfer = $this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addSuccessNotification(static::RESPONSE_NOTIFICATION_MESSAGE_SUCCESS)
            ->addActionCloseDrawer()
            ->addActionRefreshTable(static::ID_TABLE_PRODUCT_LIST)
            ->createResponse();

        return new JsonResponse($zedUiFormResponseTransfer->toArray(true, true));
    }

    /**
     * @param array<mixed> $productAbstractData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function getProductAbstractTransfer(array $productAbstractData): ProductAbstractTransfer
    {
        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->fromArray($productAbstractData, true);
        $productAbstractTransfer = $this->getFactory()
            ->createProductAbstractLocalizedAttributesExpander()
            ->expandLocalizedAttributes($productAbstractTransfer);

        return $this->getFactory()
            ->createMerchantDataExpander()
            ->expandProductAbstractWithMerchantData($productAbstractTransfer);
    }

    /**
     * @param array $productConcreteData
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function getProductConcreteTransfers(array $productConcreteData): array
    {
        $productConcreteTransfers = [];
        $productConcreteTransfers = $this->getFactory()
            ->createProductConcreteMapper()
            ->mapProductConcreteDataToProductConcreteTransfers($productConcreteData, $productConcreteTransfers);

        $productConcreteTransfers = $this->getFactory()
            ->createProductStockExpander()
            ->expandProductConcreteTransfersWithDefaultMerchantProductStock($productConcreteTransfers);

        return $this->getFactory()
            ->createProductConcreteLocalizedAttributesExpander()
            ->expandLocalizedAttributes($productConcreteTransfers);
    }

    /**
     * @param array<mixed> $viewData
     * @param array<mixed> $formData
     *
     * @return array<mixed>
     */
    protected function createMultiConcreteResponse(array $viewData, array $formData): array
    {
        return [
            'form' => $this->renderView(
                '@ProductMerchantPortalGui/Partials/create_product_abstract_with_multi_concrete_form.twig',
                $viewData,
            )->getContent(),
            'action' => $this->getFactory()->createCreateProductUrlGenerator()->getCreateUrl($formData, false),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\TableValidationResponseTransfer $tableValidationResponseTransfer
     *
     * @return array<mixed>
     */
    protected function extractErrors(TableValidationResponseTransfer $tableValidationResponseTransfer): array
    {
        $errors = [];
        /** @var \Generated\Shared\Transfer\RowValidationTransfer $rowValidationTransfer */
        foreach ($tableValidationResponseTransfer->getRowValidations() as $index => $rowValidationTransfer) {
            $errors[$index] = $rowValidationTransfer->getErrors() ? $rowValidationTransfer->toArray() : [];
        }

        return $errors;
    }
}
