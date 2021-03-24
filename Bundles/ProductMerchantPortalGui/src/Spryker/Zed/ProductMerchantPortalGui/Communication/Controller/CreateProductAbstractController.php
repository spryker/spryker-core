<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
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
                'defaultLocaleCode' => $this->findDefaultStoreDefaultLocale(),
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
    public function createWithSingleConcreteAction(Request $request)
    {
        $createProductAbstractWithSingleConcreteFormForm = $this->getFactory()
            ->createCreateProductAbstractWithSingleConcreteForm($request->query->all());
        $createProductAbstractWithSingleConcreteFormForm->handleRequest($request);

        $formData = $createProductAbstractWithSingleConcreteFormForm->getData();
        $responseData = [
            'form' => $this->renderView('@ProductMerchantPortalGui/Partials/create_product_abstract_with_single_concrete_form.twig', [
                'form' => $createProductAbstractWithSingleConcreteFormForm->createView(),
            ])->getContent(),
            'action' => $this->getCreateUrl($formData, true),
        ];

        if (!$createProductAbstractWithSingleConcreteFormForm->isSubmitted()) {
            return new JsonResponse($responseData);
        }

        if ($createProductAbstractWithSingleConcreteFormForm->isValid()) {
            //todo move to mapper
            $merchantUserTransfer = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser();
            $localeTransfers = $this->getFactory()->getLocaleFacade()->getLocaleCollection();

            $productAbstractTransfer = (new ProductAbstractTransfer())
                ->setSku($formData['sku'])
                ->setName($formData['name'])
                ->setIdMerchant($merchantUserTransfer->getIdMerchantOrFail())
            ->setIdTaxSet(2);
            $productConcreteTransfer = (new ProductConcreteTransfer())
                ->setName($formData['concreteName'])
                ->setSku($formData['concreteSku'])
                ->setIsActive(false);

            $defaultStoreDefaultLocale = $this->findDefaultStoreDefaultLocale();
            foreach ($localeTransfers as $localeTransfer) {
                $productAbstractTransfer->addLocalizedAttributes(
                    (new LocalizedAttributesTransfer())
                        ->setLocale($localeTransfer)
                        ->setName($localeTransfer->getLocaleNameOrFail() === $defaultStoreDefaultLocale ? $formData['name'] : '')
                );
                $productConcreteTransfer->addLocalizedAttributes(
                    (new LocalizedAttributesTransfer())
                        ->setLocale($localeTransfer)
                        ->setName($localeTransfer->getLocaleNameOrFail() === $defaultStoreDefaultLocale ? $formData['name'] : '')
                );
            }

            $idProductAbstract = $this->getFactory()->getProductFacade()->addProduct($productAbstractTransfer, [$productConcreteTransfer]);

            return new RedirectResponse(
                sprintf('/product-merchant-portal-gui/update-product-abstract?product-abstract-id=%s', $idProductAbstract)
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
    public function createWithMultiConcreteAction(Request $request): JsonResponse
    {
        return new JsonResponse(0);
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
     * @return string|null
     */
    protected function findDefaultStoreDefaultLocale(): ?string
    {
        $defaultStore = $this->getFactory()->getStore()::getDefaultStore();
        foreach ($this->getFactory()->getStoreFacade()->getAllStores() as $storeTransfer) {
            if ($storeTransfer->getName() === $defaultStore) {
                return array_values($storeTransfer->getAvailableLocaleIsoCodes())[0] ?? null;
            }
        }

        return null;
    }
}
