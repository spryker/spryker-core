<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 */
class SavePriceProductOfferController extends AbstractController
{
    protected const PARAM_TYPE_PRICE_PRODUCT_OFFER_IDS = 'type-price-product-offer-ids';
    protected const PARAM_VOLUME_QUANTITY = 'volume_quantity';
    protected const PARAM_PRODUCT_OFFER_ID = 'product_offer_id';

    protected const NOTIFICATION_TYPE_SUCCESS = 'success';
    protected const NOTIFICATION_TYPE_ERROR = 'error';
    protected const NOTIFICATION_SUCCESS_MESSAGE = 'Offer prices saved successfully.';

    protected const POST_ACTION_REFRESH_TABLE = 'refresh_table';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $typePriceProductOfferIds = $this->parseTypePriceProductOfferIds($request->get(static::PARAM_TYPE_PRICE_PRODUCT_OFFER_IDS));
        $volumeQuantity = $this->castId($request->get(static::PARAM_VOLUME_QUANTITY));
        $idProductOffer = $this->castId($request->get(static::PARAM_PRODUCT_OFFER_ID));

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
            return $this->errorJsonResponse($validationResponseTransfer);
        }

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceProductDimensionTransfer = $priceProductTransfer->getPriceDimensionOrFail();

            $productOfferTransfer = (new ProductOfferTransfer())->setIdProductConcrete((int)$priceProductTransfer->getIdProduct())
                ->setIdProductOffer((int)$priceProductDimensionTransfer->getIdProductOffer())
                ->addPrice($priceProductTransfer);
            $this->getFactory()->getPriceProductOfferFacade()->saveProductOfferPrices($productOfferTransfer);
        }

        return $this->successJsonResponse();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function successJsonResponse(): JsonResponse
    {
        $response = [
            'notifications' => [
                [
                    'type' => static::NOTIFICATION_TYPE_SUCCESS,
                    'message' => static::NOTIFICATION_SUCCESS_MESSAGE,
                ],
            ],
            'postActions' => [
                [
                    'type' => static::POST_ACTION_REFRESH_TABLE,
                ],
            ],
        ];

        return new JsonResponse($response);
    }

    /**
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function errorJsonResponse(ValidationResponseTransfer $validationResponseTransfer): JsonResponse
    {
        $notifications = [];
        /** @var \Generated\Shared\Transfer\ValidationErrorTransfer $validationErrorTransfer */
        $validationErrorTransfer = $validationResponseTransfer->getValidationErrors()->offsetGet(0);
        $notifications[] = [
            'type' => static::NOTIFICATION_TYPE_ERROR,
            'message' => $validationErrorTransfer->getMessage(),
        ];
        $response = [
            'notifications' => $notifications,
        ];

        return new JsonResponse($response);
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
