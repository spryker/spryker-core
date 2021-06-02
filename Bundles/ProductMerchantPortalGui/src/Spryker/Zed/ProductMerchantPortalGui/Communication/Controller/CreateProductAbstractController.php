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
    protected const RESPONSE_KEY_POST_ACTIONS = 'postActions';
    protected const RESPONSE_KEY_NOTIFICATIONS = 'notifications';
    protected const RESPONSE_KEY_TYPE = 'type';
    protected const RESPONSE_KEY_MESSAGE = 'message';

    protected const RESPONSE_TYPE_SUCCESS = 'success';
    protected const RESPONSE_TYPE_ERROR = 'error';
    protected const RESPONSE_TYPE_REFRESH_TABLE = 'refresh_table';
    protected const RESPONSE_TYPE_CLOSE_OVERLAY = 'close_overlay';
    protected const RESPONSE_MESSAGE_SUCCESS = 'Product successfully created!';
    protected const RESPONSE_MESSAGE_ERROR = 'Please resolve all errors.';
    protected const REQUEST_PARAM_NAME = 'name';
    protected const REQUEST_PARAM_SKU = 'sku';
    protected const REQUEST_PARAM_BACK = 'back';
    protected const REQUEST_PARAM_CONCRETE_PRODUCTS = 'concreteProducts';
    protected const REQUEST_PARAM_SELECTED_ATTRIBUTES = 'selectedAttributes';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductConcreteMapper::FIELD_NAME
     */
    protected const FIELD_NAME = 'name';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductConcreteMapper::FIELD_SKU
     */
    protected const FIELD_SKU = 'sku';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithSingleConcreteForm::FIELD_CONCRETE_NAME
     */
    protected const FIELD_CONCRETE_NAME = 'concreteName';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithSingleConcreteForm::FIELD_CONCRETE_SKU
     */
    protected const FIELD_CONCRETE_SKU = 'concreteSku';
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractForm::FIELD_IS_SINGLE_CONCRETE
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
                'defaultLocaleCode' => $this->getFactory()->createLocaleDataProvider()->findDefaultStoreDefaultLocale(),
            ])->getContent(),
        ];

        if (!$createProductAbstractForm->isSubmitted()) {
            return new JsonResponse($responseData);
        }

        if (!$createProductAbstractForm->isValid()) {
            $responseData = $this->addErrorNotification($responseData, static::RESPONSE_MESSAGE_ERROR);

            return new JsonResponse($responseData);
        }

        $formData = $createProductAbstractForm->getData();

        return new RedirectResponse(
            $this->getFactory()
                ->createCreateProductUrlGenerator()
                ->getCreateUrl($formData, (bool)$formData[static::FIELD_IS_SINGLE_CONCRETE])
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

        if ($request->request->get(static::REQUEST_PARAM_BACK)) {
            return new RedirectResponse(
                $this->getFactory()
                    ->createCreateProductUrlGenerator()
                    ->getCreateProductAbstractUrl($abstractProductSku, $abstractProductName)
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
            $responseData = $this->addErrorNotification($responseData, static::RESPONSE_MESSAGE_ERROR);

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

        if ($request->request->get(static::REQUEST_PARAM_BACK)) {
            return new RedirectResponse(
                $this->getFactory()
                    ->createCreateProductUrlGenerator()
                    ->getCreateProductAbstractUrl($abstractProductSku, $abstractProductName)
            );
        }

        $concreteProductsJson = (string)$request->request->get(static::REQUEST_PARAM_CONCRETE_PRODUCTS);
        $selectedAttributesJson = $request->request->get(static::REQUEST_PARAM_SELECTED_ATTRIBUTES);

        $productConcreteData = $this->getFactory()
            ->getUtilEncodingService()
            ->decodeJson($concreteProductsJson, true);
        $createProductAbstractWithMultiConcreteForm = $this->getFactory()
            ->createCreateProductAbstractWithMultiConcreteForm($request->query->all());
        $createProductAbstractWithMultiConcreteForm->handleRequest($request);

        $productAbstractTransfer = $this->getProductAbstractTransfer(
            $createProductAbstractWithMultiConcreteForm->getData()
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
                $this->createMultiConcreteResponse($viewData, $formData)
            );
        }

        $tableValidationResponseTransfer = $this->getFactory()
            ->createProductConcreteValidator()
            ->validateConcreteProducts($productConcreteData);

        if (!$tableValidationResponseTransfer->getIsSuccessOrFail()) {
            $viewData['errors'] = $this->extractErrors($tableValidationResponseTransfer);

            $responseData = $this->createMultiConcreteResponse($viewData, $formData);
            $responseData = $this->addErrorNotification($responseData, static::RESPONSE_MESSAGE_ERROR);

            return new JsonResponse($responseData);
        }

        $concreteProductTransfers = $this->getProductConcreteTransfers($productConcreteData);

        $this->getFactory()
            ->getProductFacade()
            ->addProduct($productAbstractTransfer, $concreteProductTransfers);

        return $this->getSuccessResponseAndCloseOverlay();
    }

    /**
     * @param mixed[] $responseData
     * @param string $errorMessage
     *
     * @return mixed[]
     */
    protected function addErrorNotification(array $responseData, string $errorMessage): array
    {
        $responseData[static::RESPONSE_KEY_NOTIFICATIONS] = [
            [
                static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_ERROR,
                static::RESPONSE_KEY_MESSAGE => $errorMessage,
            ],
        ];

        return $responseData;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getSuccessResponseAndCloseOverlay(): JsonResponse
    {
        $responseData = [
            static::RESPONSE_KEY_POST_ACTIONS => [
                [
                    static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_CLOSE_OVERLAY,
                ],
                [
                    static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_REFRESH_TABLE,
                ],
            ],
            static::RESPONSE_KEY_NOTIFICATIONS => [
                [
                    static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_SUCCESS,
                    static::RESPONSE_KEY_MESSAGE => static::RESPONSE_MESSAGE_SUCCESS,
                ],
            ],
        ];

        return new JsonResponse($responseData);
    }

    /**
     * @param mixed[] $productAbstractData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function getProductAbstractTransfer(array $productAbstractData): ProductAbstractTransfer
    {
        $productAbstractTransfer = $this->getFactory()
            ->createProductAbstractMapper()
            ->mapFormDataToProductAbstractTransfer($productAbstractData, new ProductAbstractTransfer());
        $productAbstractTransfer = $this->getFactory()
            ->createProductAbstractLocalizedAttributesExpander()
            ->expandLocalizedAttributes($productAbstractTransfer);
        $productAbstractTransfer = $this->getFactory()
            ->createMerchantDataExpander()
            ->expandProductAbstractWithMerchantData($productAbstractTransfer);

        return $productAbstractTransfer;
    }

    /**
     * @param array $productConcreteData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function getProductConcreteTransfers(array $productConcreteData): array
    {
        $productConcreteTransfers = [];
        $productConcreteTransfers = $this->getFactory()
            ->createProductConcreteMapper()
            ->mapProductConcreteDataToProductConcreteTransfers($productConcreteData, $productConcreteTransfers);
        $productConcreteTransfers = $this->getFactory()
            ->createProductConcreteLocalizedAttributesExpander()
            ->expandLocalizedAttributes($productConcreteTransfers);

        return $productConcreteTransfers;
    }

    /**
     * @param mixed[] $viewData
     * @param mixed[] $formData
     *
     * @return mixed[]
     */
    protected function createMultiConcreteResponse(array $viewData, array $formData): array
    {
        return [
            'form' => $this->renderView(
                '@ProductMerchantPortalGui/Partials/create_product_abstract_with_multi_concrete_form.twig',
                $viewData
            )->getContent(),
            'action' => $this->getFactory()->createCreateProductUrlGenerator()->getCreateUrl($formData, false),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\TableValidationResponseTransfer $tableValidationResponseTransfer
     *
     * @return mixed[]
     */
    protected function extractErrors(TableValidationResponseTransfer $tableValidationResponseTransfer): array
    {
        $errors = [];
        /** @var \Generated\Shared\Transfer\RowValidationTransfer $rowValidationTransfer */
        foreach ($tableValidationResponseTransfer->getRowValidations() as $index => $rowValidationTransfer) {
            $errors[$index] = !empty($rowValidationTransfer->getErrors()) ? $rowValidationTransfer->toArray() : [];
        }

        return $errors;
    }
}
