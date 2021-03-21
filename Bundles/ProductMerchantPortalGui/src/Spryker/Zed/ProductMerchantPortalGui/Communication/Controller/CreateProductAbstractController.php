<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class CreateProductAbstractController extends AbstractController
{
    protected const RESPONSE_KEY_NOTIFICATIONS = 'notifications';
    protected const RESPONSE_KEY_TYPE = 'type';
    protected const RESPONSE_KEY_MESSAGE = 'message';

    protected const RESPONSE_TYPE_ERROR = 'error';
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
        $createProductAbstractForm = $this->getFactory()->createCreateProductAbstractForm();
        $createProductAbstractForm->handleRequest($request);

        $responseData = [
            'form' => $this->renderView('@ProductMerchantPortalGui/Partials/create_product_abstract_form.twig', [
                'form' => $createProductAbstractForm->createView(),
            ])->getContent(),
        ];

        if (!$createProductAbstractForm->isSubmitted()) {
            return new JsonResponse($responseData);
        }

        if ($createProductAbstractForm->isValid()) {
            return $this->executeCreateProductAbstractFormSubmission($createProductAbstractForm);
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
        return new JsonResponse(1);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createWithMultiConcreteAction(Request $request): JsonResponse
    {
        return new JsonResponse(0);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $createProductAbstractForm
     *
     * @param \Symfony\Component\Form\FormInterface $createProductAbstractForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeCreateProductAbstractFormSubmission(FormInterface $createProductAbstractForm): RedirectResponse
    {
        $formData = $createProductAbstractForm->getData();

        $getParams = sprintf(
            '?%s=%s&%s=%s',
            CreateProductAbstractForm::FIELD_SKU,
            $formData[CreateProductAbstractForm::FIELD_SKU],
            CreateProductAbstractForm::FIELD_NAME,
            $formData[CreateProductAbstractForm::FIELD_NAME],
        );
        $redirectUrl = sprintf(
            '%s%s',
            $formData[CreateProductAbstractForm::FIELD_IS_SINGLE_CONCRETE] ? static::URL_WITH_SINGLE_CONCRETE_ACTION : static::URL_WITH_MULTI_CONCRETE_ACTION,
            $getParams
        );

        return new RedirectResponse($redirectUrl);
    }
}
