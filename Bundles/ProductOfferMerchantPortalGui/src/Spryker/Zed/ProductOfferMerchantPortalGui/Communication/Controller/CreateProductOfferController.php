<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 */
class CreateProductOfferController extends AbstractProductOfferController
{
    protected const PARAM_ID_PRODUCT = 'product-id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idProduct = $this->castId($request->get(static::PARAM_ID_PRODUCT));
        $productConcreteTransfer = $this->getFactory()->getProductFacade()->findProductConcreteById($idProduct);

        if (!$productConcreteTransfer) {
            throw new NotFoundHttpException(sprintf('Product not found for id %d.', $idProduct));
        }

        $productAbstractTransfer = $this->getFactory()->getProductFacade()->findProductAbstractById(
            $productConcreteTransfer->getFkProductAbstract()
        );
        $productOfferCreateFormDataProvider = $this->getFactory()->createProductOfferCreateFormDataProvider();
        $productOfferForm = $this->getFactory()->createProductOfferForm(
            $productOfferCreateFormDataProvider->getData($productConcreteTransfer),
            $productOfferCreateFormDataProvider->getOptions($productAbstractTransfer)
        );
        $productOfferForm->handleRequest($request);
        $initialData = [];
        $isPriceProductOffersValid = true;

        if ($productOfferForm->isSubmitted() && $productOfferForm->isValid()) {
            $priceProductOfferCollectionValidationResponseTransfer = $this->getFactory()
                ->getPriceProductOfferFacade()
                ->validateProductOfferPrices($productOfferForm->getData()->getPrices());

            $isPriceProductOffersValid = $priceProductOfferCollectionValidationResponseTransfer->getIsSuccessful();

            if (!$isPriceProductOffersValid) {
                $initialData = $this->prepareInitialDataForGuiTableConfiguration(
                    $priceProductOfferCollectionValidationResponseTransfer,
                    $request
                );
            } else {
                $this->getFactory()->getProductOfferFacade()->create($productOfferForm->getData());
            }
        }

        $priceProductOfferTableConfiguration = $this->getFactory()
            ->createPriceProductOfferCreateGuiTableConfigurationProvider()
            ->getConfiguration($initialData);

        return $this->getResponse(
            $productOfferForm,
            $productConcreteTransfer,
            $productAbstractTransfer,
            $priceProductOfferTableConfiguration,
            $isPriceProductOffersValid
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function priceTableDataAction(Request $request): Response
    {
        return $this->getFactory()->getGuiTableHttpDataRequestExecutor()->execute(
            $request,
            $this->getFactory()->createProductOfferPriceTableDataProvider(),
            $this->getFactory()->createPriceProductOfferCreateGuiTableConfigurationProvider()->getConfiguration()
        );
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $productOfferForm
     *
     * @param \Symfony\Component\Form\FormInterface $productOfferForm
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $priceProductOfferTableConfiguration
     * @param bool $isPriceProductOffersValid
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getResponse(
        FormInterface $productOfferForm,
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer,
        GuiTableConfigurationTransfer $priceProductOfferTableConfiguration,
        bool $isPriceProductOffersValid
    ): JsonResponse {
        $localeTransfer = $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();

        $responseData = [
            'form' => $this->renderView('@ProductOfferMerchantPortalGui/Partials/offer_form.twig', [
                'form' => $productOfferForm->createView(),
                'product' => $productConcreteTransfer,
                'productName' => $this->getFactory()->createProductNameBuilder()->buildProductConcreteName($productConcreteTransfer, $localeTransfer),
                'productAttributes' => $this->getProductAttributes($localeTransfer, $productConcreteTransfer, $productAbstractTransfer),
                'priceProductOfferTableConfiguration' => $priceProductOfferTableConfiguration,
            ])->getContent(),
        ];

        if (!$productOfferForm->isSubmitted()) {
            return new JsonResponse($responseData);
        }

        if ($productOfferForm->isValid() && $isPriceProductOffersValid) {
            $responseData['postActions'] = [[
                'type' => 'redirect',
                'url' => '/product-offer-merchant-portal-gui/product-offers',
            ]];

            $this->addSuccessMessage('The Offer is saved.');
        }

        $responseData = $this->addValidationNotifications($responseData, $productOfferForm, $isPriceProductOffersValid);

        return new JsonResponse($responseData);
    }
}
