<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractForm;
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
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createWithSingleConcreteAction(Request $request): JsonResponse
    {
        $createProductAbstractWithSingleConcreteForm = $this->getFactory()
            ->createCreateProductAbstractWithSingleConcreteForm($request->query->all());
        $createProductAbstractWithSingleConcreteForm->handleRequest($request);

        $formData = $createProductAbstractWithSingleConcreteForm->getData();
        $responseData = [
            'form' => $this->renderView('@ProductMerchantPortalGui/Partials/create_product_abstract_with_single_concrete_form.twig', [
                'form' => $createProductAbstractWithSingleConcreteForm->createView(),
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

        $responseData[static::RESPONSE_KEY_NOTIFICATIONS] = [[
            static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_ERROR,
            static::RESPONSE_KEY_MESSAGE => static::RESPONSE_MESSAGE_ERROR,
        ]];

        return new JsonResponse($responseData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createWithMultiConcreteAction(Request $request): JsonResponse
    {
        $createProductAbstractWithMultiConcreteForm = $this->getFactory()
            ->createCreateProductAbstractWithMultiConcreteForm($request->query->all());
        $createProductAbstractWithMultiConcreteForm->handleRequest($request);

        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setSku($request->query->get('sku'));
        $productAbstractTransfer->setName($request->query->get('name'));


        $formData = $createProductAbstractWithMultiConcreteForm->getData();
        $responseData = [
            'form' => $this->renderView('@ProductMerchantPortalGui/Partials/create_product_abstract_with_multi_concrete_form.twig', [
                'form' => $createProductAbstractWithMultiConcreteForm->createView(),
                'superProductManagementAttributes' => $this->getSuperAttributes(),
                'productAbstract' => $productAbstractTransfer,
            ])->getContent(),
            'action' => $this->getCreateUrl($formData, false),
        ];

        return new JsonResponse($responseData);
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
     * @return string[][]
     */
    protected function getSuperAttributes(): array
    {
        $productManagementAttributeTransfers = $this->getFactory()
            ->getProductAttributeFacade()
            ->getProductAttributeCollection();

        $superAttributes = [];
        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            if ($productManagementAttributeTransfer->getIsSuper()) {
                $values = [];
                foreach ($productManagementAttributeTransfer->getValues() as $productManagementAttributeValueTransfer) {
                    $values[] = [
                        'title' => $productManagementAttributeValueTransfer->getValueOrFail(),
                        'value' => $productManagementAttributeValueTransfer->getValueOrFail(),
                    ];
                }

                $superAttributes[] = [
                    'title' => $productManagementAttributeTransfer->getKeyOrFail(),
                    'value' => $productManagementAttributeTransfer->getKeyOrFail(),
                    'values' => $values,
                ];
            }
        }

        return $superAttributes;
    }
}
