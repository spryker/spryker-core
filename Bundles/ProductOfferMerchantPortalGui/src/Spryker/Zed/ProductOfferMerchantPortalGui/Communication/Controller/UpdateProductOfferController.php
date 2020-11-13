<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferResponseTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
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
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\ProductOfferPriceGuiTableConfigurationProvider::COL_STORE
     */
    protected const PARAM_STORE = 'store';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\ProductOfferPriceGuiTableConfigurationProvider::COL_STORE
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

        if ($productOfferForm->isSubmitted() && $productOfferForm->isValid()) {
            $productOfferResponseTransfer = $this->getFactory()->getProductOfferFacade()->update($productOfferForm->getData());
        }

        $productOfferResponseTransfer = $productOfferResponseTransfer ?? new ProductOfferResponseTransfer();
        $productOfferResponseTransfer->setProductOffer($productOfferTransfer);

        $priceProductOfferTableConfiguration = $this->getFactory()
            ->createProductOfferPriceGuiTableConfigurationProvider()
            ->getConfiguration($idProductOffer);

        return $this->getResponse(
            $productOfferForm,
            $productConcreteTransfer,
            $productAbstractTransfer,
            $productOfferResponseTransfer,
            $priceProductOfferTableConfiguration
        );
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $productOfferForm
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductOfferResponseTransfer $productOfferResponseTransfer
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $priceProductOfferTableConfiguration
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getResponse(
        FormInterface $productOfferForm,
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer,
        ProductOfferResponseTransfer $productOfferResponseTransfer,
        GuiTableConfigurationTransfer $priceProductOfferTableConfiguration
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

        if ($productOfferForm->isValid() && $productOfferResponseTransfer->getIsSuccessful()) {
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

        return new JsonResponse($responseData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function priceTableDataAction(Request $request): Response
    {
        $idProductOffer = $this->castId($request->get(static::PARAM_ID_PRODUCT_OFFER));

        return $this->getFactory()->getGuiTableHttpDataRequestExecutor()->execute(
            $request,
            $this->getFactory()->createProductOfferPriceTableDataProvider($idProductOffer),
            $this->getFactory()->createProductOfferPriceGuiTableConfigurationProvider()->getConfiguration($idProductOffer)
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function savePricesAction(Request $request)
    {
        $idProductOffer = $this->castId($request->get(static::PARAM_ID_PRODUCT_OFFER));
        $store = $request->get(static::PARAM_STORE);
        $currency = $request->get(static::PARAM_CURRENCY);
        $typePriceProductOfferIds = $request->get(static::PARAM_TYPE_PRICE_PRODUCT_OFFER_IDS);

        $storeTransfer = $this->getFactory()->getStoreFacade()->getStoreByName($store);
        $currencyTransfer = $this->getFactory()->getCurrencyFacade()->findCurrencyByIsoCode($currency);

        $priceDimensionTransfer = new PriceProductDimensionTransfer();

        if ($idPriceProductOffer) {
            $priceDimensionTransfer->setIdPriceProductOffer($idPriceProductOffer);
        }

        $priceProductTransfers = [];

        foreach ($this->getFactory()->getPriceProductFacade()->getPriceTypeValues() as $priceTypeTransfer) {
            $netAmount = $request->get(mb_strtolower($priceTypeTransfer->getName()) . '_net');
            $grossAmount = $request->get(mb_strtolower($priceTypeTransfer->getName()) . '_gross');
            $priceProductTransfers[] = (new PriceProductTransfer())
                ->setFkPriceType($priceTypeTransfer->getIdPriceType())
                ->setMoneyValue(
                    (new MoneyValueTransfer())
                    ->setFkStore($storeTransfer->getIdStore())
                    ->setNetAmount($netAmount)
                    ->setGrossAmount($grossAmount)
                    ->setFkCurrency($currencyTransfer->getIdCurrency())
                );
        }

        $productOfferTransfer = (new ProductOfferTransfer())
            ->setPrices(new ArrayObject($priceProductTransfers));

        $productOfferResponseTransfer = $this->getFactory()
            ->getPriceProductOfferFacade()
            ->validateProductOfferPrices($productOfferTransfer);

        if ($productOfferResponseTransfer->getErrors()->count()) {
            foreach ($productOfferResponseTransfer->getErrors() as $productOfferErrorTransfer) {
                $responseData['notifications'][] = [
                    'type' => 'error',
                    'message' => $productOfferErrorTransfer->getMessage(),
                ];
            }

            return new JsonResponse($responseData);
        }

        $this->getFactory()->getPriceProductOfferFacade()->saveProductOfferPrices($productOfferTransfer);

        $responseData['postActions'] = [
            [
                'type' => 'refresh_table',
            ],
        ];
        $responseData['notifications'] = [[
            'type' => 'success',
            'message' => 'Offer prices saved successfuly.',
        ]];

        return new JsonResponse($responseData);
    }
}
