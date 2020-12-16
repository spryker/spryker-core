<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller;

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

        $initialData = $this->getDefaultInitialData($request, $productOfferForm->getName());
        $productOfferResponseTransfer = new ProductOfferResponseTransfer();
        $productOfferResponseTransfer->setProductOffer($productOfferTransfer);

        if (!$productOfferForm->isSubmitted()) {
            return $this->getResponse(
                $productOfferForm,
                $productConcreteTransfer,
                $productAbstractTransfer,
                $productOfferResponseTransfer,
                $idProductOffer,
                $initialData
            );
        }

        $validationResponseTransfer = $this->getFactory()
            ->getPriceProductOfferFacade()
            ->validateProductOfferPrices($productOfferForm->getData()->getPrices());

        if (!$productOfferForm->isValid() || !$validationResponseTransfer->getIsSuccess()) {
            $initialData = $this->getFactory()
                ->createPriceProductOfferMapper()
                ->mapValidationResponseTransferToInitialDataErrors(
                    $validationResponseTransfer,
                    $initialData
                );

            return $this->getResponse(
                $productOfferForm,
                $productConcreteTransfer,
                $productAbstractTransfer,
                $productOfferResponseTransfer,
                $idProductOffer,
                $initialData
            );
        }

        $productOfferResponseTransfer = $this->getFactory()->getProductOfferFacade()->update($productOfferForm->getData());

        return $this->getResponse(
            $productOfferForm,
            $productConcreteTransfer,
            $productAbstractTransfer,
            $productOfferResponseTransfer,
            $idProductOffer,
            $initialData
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
     * @phpstan-param array<mixed> $initialData
     *
     * @param \Symfony\Component\Form\FormInterface $productOfferForm
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductOfferResponseTransfer $productOfferResponseTransfer
     * @param int $idProductOffer
     * @param array $initialData
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getResponse(
        FormInterface $productOfferForm,
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer,
        ProductOfferResponseTransfer $productOfferResponseTransfer,
        int $idProductOffer,
        array $initialData
    ): JsonResponse {
        $localeTransfer = $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();

        $priceProductOfferTableConfiguration = $this->getFactory()
            ->createPriceProductOfferUpdateGuiTableConfigurationProvider()
            ->getConfiguration($idProductOffer, $initialData);

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

        $isPriceProductOffersValid = count($initialData['errors']) === 0;

        if (
            $productOfferForm->isValid()
            && $productOfferResponseTransfer->getIsSuccessful()
            && $isPriceProductOffersValid
        ) {
            $responseData = [
                'postActions' => [
                    ['type' => 'close_overlay'],
                    ['type' => 'refresh_table'],
                ],
                'notifications' => [
                    [
                        'type' => 'success',
                        'message' => 'The Offer is saved.',
                    ],
                ],
            ];

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

        $responseData = $this->addValidationNotifications($responseData);

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
            $this->getFactory()->createPriceProductOfferUpdateGuiTableConfigurationProvider()->getConfiguration($idProductOffer, [])
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
