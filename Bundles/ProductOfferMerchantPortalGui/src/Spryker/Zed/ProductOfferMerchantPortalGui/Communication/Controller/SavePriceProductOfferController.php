<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 */
class SavePriceProductOfferController extends AbstractPriceProductOfferController
{
    protected const PARAM_TYPE_PRICE_PRODUCT_OFFER_IDS = 'type-price-product-offer-ids';
    protected const PARAM_VOLUME_QUANTITY = 'volume_quantity';
    protected const PARAM_PRODUCT_OFFER_ID = 'product_offer_id';

    protected const RESPONSE_NOTIFICATION_MESSAGE_SUCCESS = 'Offer prices saved successfully.';

    protected const POST_ACTION_REFRESH_TABLE = 'refresh_table';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $typePriceProductOfferIds = $this->parseTypePriceProductOfferIds($request->get(static::PARAM_TYPE_PRICE_PRODUCT_OFFER_IDS));
        $volumeQuantity = $this->castId($request->get(static::PARAM_VOLUME_QUANTITY));
        $idProductOffer = $this->castId($request->get(static::PARAM_PRODUCT_OFFER_ID));

        $productOfferTransfer = $this->getFactory()->getProductOfferFacade()->findOne(
            (new ProductOfferCriteriaTransfer())->setIdProductOffer($idProductOffer)
        );

        if (!$productOfferTransfer) {
            throw new NotFoundHttpException(sprintf('Product offer is not found for id %d.', $idProductOffer));
        }

        $currentMerchantReference = $this->getFactory()
            ->getMerchantUserFacade()
            ->getCurrentMerchantUser()
            ->getMerchantOrFail()
            ->getMerchantReferenceOrFail();

        if ($productOfferTransfer->getMerchantReferenceOrFail() !== $currentMerchantReference) {
            return new JsonResponse(['success' => false]);
        }

        $requestData = $this->getFactory()->getUtilEncodingService()->decodeJson((string)$request->getContent(), true)['data'];

        $priceProductTransfers = $this->getFactory()->createPriceProductDataProvider()->getPriceProductOfferPrices(
            $typePriceProductOfferIds,
            $requestData,
            $volumeQuantity,
            $idProductOffer
        );

        $priceProductOfferTransfer = (new PriceProductOfferTransfer())
            ->setProductOffer((new ProductOfferTransfer())->setPrices(new ArrayObject($priceProductTransfers)));

        $priceProductOfferCollectionTransfer = (new PriceProductOfferCollectionTransfer())
            ->addPriceProductOffer($priceProductOfferTransfer);

        $validationResponseTransfer = $this->getFactory()
            ->createPriceProductOfferValidator()
            ->validatePriceProductOfferCollection($priceProductOfferCollectionTransfer);

        if (!$validationResponseTransfer->getIsSuccess()) {
            return $this->createErrorJsonResponse(
                $validationResponseTransfer
                    ->getValidationErrors()
                    ->offsetGet(0)
                    ->getMessage()
            );
        }

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceProductDimensionTransfer = $priceProductTransfer->getPriceDimensionOrFail();

            $productOfferTransfer = (new ProductOfferTransfer())->setIdProductConcrete((int)$priceProductTransfer->getIdProduct())
                ->setIdProductOffer((int)$priceProductDimensionTransfer->getIdProductOffer())
                ->addPrice($priceProductTransfer);
            $this->getFactory()->getPriceProductOfferFacade()->saveProductOfferPrices($productOfferTransfer);
        }

        return $this->createSuccessJsonResponse();
    }

    /**
     * @param string $requestedTypePriceProductOfferIds
     *
     * @return int[]
     */
    protected function parseTypePriceProductOfferIds(string $requestedTypePriceProductOfferIds): array
    {
        $requestedTypePriceProductOfferIds = explode(',', $requestedTypePriceProductOfferIds);
        $typePriceProductOfferIds = [];

        foreach ($requestedTypePriceProductOfferIds as $key => $requestedTypePriceProductOfferId) {
            $typePriceProductOfferId = explode(':', $requestedTypePriceProductOfferId);
            $typePriceProductOfferIds[$typePriceProductOfferId[0]] = (int)$typePriceProductOfferId[1];
        }

        return $typePriceProductOfferIds;
    }
}
