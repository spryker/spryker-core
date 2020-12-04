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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 */
class UpdateProductOfferController extends AbstractProductOfferController
{
    protected const PARAM_ID_PRODUCT_OFFER = 'product-offer-id';

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
            throw new NotFoundHttpException(sprintf('Product offer is not found for id %d.', $idProductOffer));
        }

        /** @var int $idProductConcrete */
        $idProductConcrete = $productOfferTransfer->requireIdProductConcrete()->getIdProductConcrete();
        $productConcreteTransfer = $this->getFactory()->getProductFacade()->findProductConcreteById($idProductConcrete);
        if (!$productConcreteTransfer) {
            throw new NotFoundHttpException(sprintf('Product is not found for id %d.', $idProductConcrete));
        }

        /** @var int $idProductAbstract */
        $idProductAbstract = $productConcreteTransfer->requireFkProductAbstract()->getFkProductAbstract();
        /** @var \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer */
        $productAbstractTransfer = $this->getFactory()->getProductFacade()->findProductAbstractById($idProductAbstract);

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

        return $this->getResponse($productOfferForm, $productConcreteTransfer, $productAbstractTransfer, $productOfferResponseTransfer);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $productOfferForm
     *
     * @param \Symfony\Component\Form\FormInterface $productOfferForm
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductOfferResponseTransfer $productOfferResponseTransfer
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getResponse(
        FormInterface $productOfferForm,
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer,
        ProductOfferResponseTransfer $productOfferResponseTransfer
    ): JsonResponse {
        $localeTransfer = $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();

        $productOfferTransfer = $productOfferResponseTransfer->getProductOffer();
        $productOfferReference = $productOfferTransfer ? $productOfferTransfer->getProductOfferReference() : null;

        $responseData = [
            'form' => $this->renderView('@ProductOfferMerchantPortalGui/Partials/offer_form.twig', [
                'form' => $productOfferForm->createView(),
                'product' => $productConcreteTransfer,
                'productName' => $this->getFactory()->createProductNameBuilder()->buildProductConcreteName($productConcreteTransfer, $localeTransfer),
                'productAttributes' => $this->getProductAttributes($localeTransfer, $productConcreteTransfer, $productAbstractTransfer),
                'productOfferReference' => $productOfferReference,
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
}
