<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferResponseTransfer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 */
class UpdateProductOfferController extends AbstractProductOfferController
{
    protected const PARAM_ID_PRODUCT_OFFER = 'product-offer-id';
    protected const PARAM_TYPE_PRICE_PRODUCT_OFFER_IDS = 'type-price-product-offer-ids';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\PriceProductOfferUpdateGuiTableConfigurationProvider::COL_STORE
     */
    protected const PARAM_STORE = 'store';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\PriceProductOfferUpdateGuiTableConfigurationProvider::COL_STORE
     */
    protected const PARAM_CURRENCY = 'currency';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idProductOffer = $this->castId($request->get(static::PARAM_ID_PRODUCT_OFFER));
        $productOfferUpdateFormDataProvider = $this->getFactory()->createProductOfferUpdateFormDataProvider();
        $productOfferTransfer = $productOfferUpdateFormDataProvider->getData($idProductOffer);

        if (!$productOfferTransfer) {
            throw new NotFoundHttpException(sprintf('Product offer not found for id %d.', $idProductOffer));
        }

        $productConcreteTransfer = $this->getFactory()->getProductFacade()->findProductConcreteById(
            $productOfferTransfer->getIdProductConcrete()
        );
        $productAbstractTransfer = $this->getFactory()->getProductFacade()->findProductAbstractById(
            $productConcreteTransfer->getFkProductAbstract()
        );
        $productOfferForm = $this->getFactory()->createProductOfferForm(
            $productOfferTransfer,
            $productOfferUpdateFormDataProvider->getOptions($productAbstractTransfer)
        );
        $productOfferForm->handleRequest($request);

        $initialData = [];
        $priceProductOfferCollectionValidationResponseTransfer = null;
        $productOfferResponseTransfer = new ProductOfferResponseTransfer();
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
                $productOfferResponseTransfer = $this->getFactory()->getProductOfferFacade()->update($productOfferForm->getData());
            }
        }

        $productOfferResponseTransfer->setProductOffer($productOfferTransfer);

        $priceProductOfferTableConfiguration = $this->getFactory()
            ->createPriceProductOfferUpdateGuiTableConfigurationProvider()
            ->getConfiguration($idProductOffer, $initialData);

        return $this->getResponse(
            $productOfferForm,
            $productConcreteTransfer,
            $productAbstractTransfer,
            $productOfferResponseTransfer,
            $priceProductOfferTableConfiguration,
            $isPriceProductOffersValid
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deletePricesAction(Request $request): JsonResponse
    {
        return $this->getFactory()
            ->createDeletePricesAction()
            ->execute($request);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $productOfferForm
     *
     * @param \Symfony\Component\Form\FormInterface $productOfferForm
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductOfferResponseTransfer $productOfferResponseTransfer
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $priceProductOfferTableConfiguration
     * @param bool $isPriceProductOffersValid
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getResponse(
        FormInterface $productOfferForm,
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer,
        ProductOfferResponseTransfer $productOfferResponseTransfer,
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
                'productOfferReference' => $productOfferResponseTransfer->getProductOffer()->getProductOfferReference(),
                'priceProductOfferTableConfiguration' => $priceProductOfferTableConfiguration,
            ])->getContent(),
        ];

        if (!$productOfferForm->isSubmitted()) {
            return new JsonResponse($responseData);
        }

        if (
            $productOfferForm->isValid()
            && $productOfferResponseTransfer->getIsSuccessful()
            && $isPriceProductOffersValid
        ) {
            $responseData['postActions'] = [
                [
                    'type' => 'close_overlay',
                ],
                [
                    'type' => 'refresh_table',
                ],
            ];
            $responseData['notifications'] = [[
                'type' => 'success',
                'message' => 'The Offer is saved.',
            ]];

            return new JsonResponse($responseData);
        }

        if ($productOfferResponseTransfer->getErrors()->count()) {
            foreach ($productOfferResponseTransfer->getErrors() as $productOfferErrorTransfer) {
                $responseData['notifications'][] = [
                    'type' => 'error',
                    'message' => $productOfferErrorTransfer->getMessage(),
                ];
            }
        }

        $responseData = $this->addValidationNotifications($responseData, $productOfferForm, $isPriceProductOffersValid);

        return new JsonResponse($responseData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function priceTableDataAction(Request $request): Response
    {
        $idProductOffer = (int)$request->get(static::PARAM_ID_PRODUCT_OFFER);

        return $this->getFactory()->getGuiTableHttpDataRequestExecutor()->execute(
            $request,
            $this->getFactory()->createProductOfferPriceTableDataProvider($idProductOffer),
            $this->getFactory()->createPriceProductOfferUpdateGuiTableConfigurationProvider()->getConfiguration($idProductOffer)
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function savePricesAction(Request $request)
    {
        return $this->getFactory()->createSavePricesAction()->execute($request);
    }
}
