<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractForm;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithSingleConcreteForm;
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
    protected const REQUEST_PARAM_CONCRETE_PRODUCTS = 'concreteProducts';
    protected const REQUEST_PARAM_SELECTED_ATTRIBUTES = 'selectedAttributes';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\CreateProductAbstractController::indexAction()
     */
    protected const URL_INDEX_ACTION = '/product-merchant-portal-gui/create-product-abstract';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\CreateProductAbstractController::createWithSingleConcreteAction()
     */
    protected const URL_WITH_SINGLE_CONCRETE_ACTION = '/product-merchant-portal-gui/create-product-abstract/create-with-single-concrete';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\CreateProductAbstractController::createWithMultiConcreteAction()
     */
    protected const URL_WITH_MULTI_CONCRETE_ACTION = '/product-merchant-portal-gui/create-product-abstract/create-with-multi-concrete';

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

        if ($createProductAbstractForm->isValid()) {
            $formData = $createProductAbstractForm->getData();

            return new RedirectResponse(
                $this->getCreateUrl($formData, (bool)$formData[CreateProductAbstractForm::FIELD_IS_SINGLE_CONCRETE])
            );
        }

        $responseData[static::RESPONSE_KEY_NOTIFICATIONS] = [[
            static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_ERROR,
            static::RESPONSE_KEY_MESSAGE => static::RESPONSE_MESSAGE_ERROR,
        ]];

        return new JsonResponse($responseData);
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

        /** @TODO Fetch right param */
        if ($request->request->get('back')) {
            return new RedirectResponse(
                $this->getCreateProductAbstractUrl($abstractProductSku, $abstractProductName)
            );
        }

        $isNotPost = !$request->isMethod(Request::METHOD_POST);

        $defaultData = [
            CreateProductAbstractWithSingleConcreteForm::FIELD_NAME => $abstractProductName,
            CreateProductAbstractWithSingleConcreteForm::FIELD_SKU => $abstractProductSku,
            CreateProductAbstractWithSingleConcreteForm::FIELD_CONCRETE_NAME => $abstractProductName,
            CreateProductAbstractWithSingleConcreteForm::FIELD_CONCRETE_SKU => $abstractProductSku,
            CreateProductAbstractWithSingleConcreteForm::FIELD_USE_ABSTRACT_PRODUCT_NAME => $isNotPost,
            CreateProductAbstractWithSingleConcreteForm::FIELD_AUTOGENERATE_SKU => $isNotPost,
        ];

        $createProductAbstractWithSingleConcreteForm = $this->getFactory()
            ->createCreateProductAbstractWithSingleConcreteForm($defaultData);
        $createProductAbstractWithSingleConcreteForm->handleRequest($request);

        $formData = $createProductAbstractWithSingleConcreteForm->getData();
        $responseData = [
            'form' => $this->renderView('@ProductMerchantPortalGui/Partials/create_product_abstract_with_single_concrete_form.twig', [
                'form' => $createProductAbstractWithSingleConcreteForm->createView(),
                'backActionUrl' => $this->getCreateProductAbstractUrl($abstractProductSku, $abstractProductName),
            ])->getContent(),
            'action' => $this->getCreateUrl($formData, true),
        ];

        if (!$createProductAbstractWithSingleConcreteForm->isSubmitted()) {
            return new JsonResponse($responseData);
        }

        if ($createProductAbstractWithSingleConcreteForm->isValid()) {
            $this->getFactory()
                ->createCreateProductAbstractWithSingleConcreteFormSubmitter()
                ->executeFormSubmission($createProductAbstractWithSingleConcreteForm);

            return $this->getSuccessResponseAndCloseOverlay();
        }

        $responseData[static::RESPONSE_KEY_NOTIFICATIONS] = [[
            static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_ERROR,
            static::RESPONSE_KEY_MESSAGE => static::RESPONSE_MESSAGE_ERROR,
        ]];

        return new JsonResponse($responseData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createWithMultiConcreteAction(Request $request): JsonResponse
    {
        $abstractProductName = $request->get(static::REQUEST_PARAM_NAME);
        $abstractProductSku = $request->get(static::REQUEST_PARAM_SKU);

        /** @TODO Fetch right param */
        if ($request->request->get('back')) {
            return new RedirectResponse(
                $this->getCreateProductAbstractUrl($abstractProductSku, $abstractProductName)
            );
        }

        $concreteProductsJson = $request->request->get(static::REQUEST_PARAM_CONCRETE_PRODUCTS);
        $selectedAttributesJson = $request->request->get(static::REQUEST_PARAM_SELECTED_ATTRIBUTES);

        $concreteProducts = $this->getFactory()
            ->getUtilEncodingService()
            ->decodeJson($concreteProductsJson, true);
        $createProductAbstractWithMultiConcreteForm = $this->getFactory()
            ->createCreateProductAbstractWithMultiConcreteForm($request->query->all());
        $createProductAbstractWithMultiConcreteForm->handleRequest($request);

        $productAbstractTransfer = $this->getFactory()
            ->createProductAbstractMapper()
            ->mapFormDataToProductAbstractTransfer($createProductAbstractWithMultiConcreteForm);

        $formData = $createProductAbstractWithMultiConcreteForm->getData();
        $responseData = [
            'form' => $this->renderView('@ProductMerchantPortalGui/Partials/create_product_abstract_with_multi_concrete_form.twig', [
                'form' => $createProductAbstractWithMultiConcreteForm->createView(),
                'superProductManagementAttributes' => $this->getSuperAttributes(),
                'productAbstract' => $productAbstractTransfer,
                'concreteProductsJson' => $concreteProductsJson,
                'selectedAttributesJson' => $selectedAttributesJson,
            ])->getContent(),
            'action' => $this->getCreateUrl($formData, false),
        ];

        if (!$request->isMethod(Request::METHOD_POST)) {
            return new JsonResponse($responseData);
        }

        $validationResponseTransfer = $this->getFactory()
            ->createProductConcreteValidator()
            ->validateConcreteProducts($concreteProducts);

        if (!$validationResponseTransfer->getIsSuccessOrFail()) {
            return new JsonResponse(
                $this->addErrorNotifications($responseData, $validationResponseTransfer)
            );
        }

        $concreteProductTransfers = $this->mapRequestDataToProductConcreteTransfer($concreteProducts);

        $this->addProduct($productAbstractTransfer, $concreteProductTransfers);

        return $this->getSuccessResponseAndCloseOverlay();
    }

    /**
     * @param mixed[] $formData
     * @param bool $isSingleConcrete
     *
     * @return string
     */
    protected function getCreateUrl(array $formData, bool $isSingleConcrete): string
    {
        $getParams = sprintf(
            '?%s=%s&%s=%s',
            CreateProductAbstractForm::FIELD_SKU,
            $formData[CreateProductAbstractForm::FIELD_SKU],
            CreateProductAbstractForm::FIELD_NAME,
            $formData[CreateProductAbstractForm::FIELD_NAME]
        );

        return sprintf(
            '%s%s',
            $isSingleConcrete ? static::URL_WITH_SINGLE_CONCRETE_ACTION : static::URL_WITH_MULTI_CONCRETE_ACTION,
            $getParams
        );
    }

    /**
     * @param string $sku
     * @param string $name
     *
     * @return string
     */
    protected function getCreateProductAbstractUrl(string $sku, string $name): string
    {
        return sprintf(
            '%s?%s=%s&%s=%s',
            static::URL_INDEX_ACTION,
            CreateProductAbstractForm::FIELD_SKU,
            $sku,
            CreateProductAbstractForm::FIELD_NAME,
            $name
        );
    }

    /**
     * @return string[][]
     */
    protected function getSuperAttributes(): array
    {
        return $this->getFactory()
            ->createSuperAttributesDataProvider()
            ->getSuperAttributes();
    }

    /**
     * @param array $response
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     *
     * @return array
     */
    protected function addErrorNotifications(
        array $response,
        ValidationResponseTransfer $validationResponseTransfer
    ): array {
        $notifications = [];

        $validationErrorTransfers = $validationResponseTransfer->getValidationErrors();
        foreach ($validationErrorTransfers as $validationErrorTransfer) {
            $notifications[] = [
                static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_ERROR,
                static::RESPONSE_KEY_MESSAGE => $validationErrorTransfer->getMessage(),
            ];
        }

        $response[static::RESPONSE_KEY_NOTIFICATIONS] = $notifications;

        return $response;
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
     * @param array $concreteProducts
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function mapRequestDataToProductConcreteTransfer(array $concreteProducts): array
    {
        return $this->getFactory()
            ->createProductConcreteMapper()
            ->mapRequestDataToProductConcreteTransfer($concreteProducts);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $concreteProductTransfers
     *
     * @return int
     */
    protected function addProduct(
        ProductAbstractTransfer $productAbstractTransfer,
        array $concreteProductTransfers
    ): int {
        return $this->getFactory()
            ->getProductFacade()
            ->addProduct($productAbstractTransfer, $concreteProductTransfers);
    }
}
