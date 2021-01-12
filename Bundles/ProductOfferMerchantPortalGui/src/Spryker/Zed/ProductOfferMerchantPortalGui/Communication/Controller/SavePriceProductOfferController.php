<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
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
    protected const PARAM_PRODUCT_OFFER_ID = 'product-offer-id';

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

        $requestData = $this->getFactory()->getUtilEncodingService()->decodeJson((string)$request->getContent(), true)['data'];

        $idProductOffer = $request->get(static::PARAM_PRODUCT_OFFER_ID);
        $priceProductTransfers = $this->getPriceProductTransfers($idProductOffer, $typePriceProductOfferIds, $requestData);
        $priceProductTransfers = $this->getFactory()
            ->createPriceProductOfferMapper()
            ->mapRequestDataToPriceProductTransfers($requestData, $priceProductTransfers);

        $priceProductOfferTransfer = (new PriceProductOfferTransfer())
            ->setProductOffer((new ProductOfferTransfer())->setPrices(new ArrayObject($priceProductTransfers)));

        $priceProductOfferCollectionTransfer = (new PriceProductOfferCollectionTransfer())
            ->addPriceProductOffer($priceProductOfferTransfer);

        $validationResponseTransfer = $this->getFactory()->getPriceProductOfferFacade()->validateProductOfferPrices($priceProductOfferCollectionTransfer);
        if (!$validationResponseTransfer->getIsSuccess()) {
            return $this->errorJsonResponse($validationResponseTransfer);
        }

        foreach ($priceProductTransfers as $priceProductTransfer) {
            /** @var \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer */
            $priceProductDimensionTransfer = $priceProductTransfer->getPriceDimensionOrFail();

            $productOfferTransfer = (new ProductOfferTransfer())->setIdProductConcrete((int)$priceProductTransfer->getIdProduct())
                ->setIdProductOffer((int)$priceProductDimensionTransfer->getIdProductOffer())
                ->addPrice($priceProductTransfer);
            $this->getFactory()->getPriceProductOfferFacade()->saveProductOfferPrices($productOfferTransfer);
        }

        return $this->successJsonResponse();
    }

    /**
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param int $idProductOffer
     * @param int[] $typePriceProductOfferIds
     * @param string[] $data
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function getPriceProductTransfers(
        int $idProductOffer,
        array $typePriceProductOfferIds,
        array $data
    ): ArrayObject {
        $priceProductOfferIds = [];
        $key = (string)key($data);
        $priceTypeName = mb_strtoupper((string)strstr($key, '[', true));
        if (strpos($key, '[') !== false) {
            if (array_key_exists($priceTypeName, $typePriceProductOfferIds)) {
                $priceProductOfferIds[] = $typePriceProductOfferIds[$priceTypeName];
            }
        } else {
            $priceProductOfferIds = $typePriceProductOfferIds;
        }

        if (!$priceProductOfferIds) {
            $priceProductTransfers = new ArrayObject();

            /** @var \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer */
            $productOfferTransfer = $this->getFactory()
                ->getProductOfferFacade()
                ->findOne((new ProductOfferCriteriaFilterTransfer())->setIdProductOffer($idProductOffer));

            /** @var string $concreteSku */
            $concreteSku = $productOfferTransfer->getConcreteSku();
            /** @var int $idProductConcrete */
            $idProductConcrete = $this->getFactory()
                ->getProductFacade()
                ->findProductConcreteIdBySku($concreteSku);
            $productOfferTransfer->setIdProductConcrete($idProductConcrete);

            $priceProductTransfer = $this->getFactory()
                ->createPriceProductOfferMapper()
                ->mapProductOfferTransferToPriceProductTransfer($productOfferTransfer, new PriceProductTransfer());
            $priceProductTransfer = $this->setPriceTypeToPriceProduct($priceTypeName, $priceProductTransfer);
            $priceProductTransfers->append($priceProductTransfer);

            return $priceProductTransfers;
        }

        $priceProductOfferCriteriaTransfer = new PriceProductOfferCriteriaTransfer();
        $priceProductOfferCriteriaTransfer->setPriceProductOfferIds($priceProductOfferIds);

        return $this->getFactory()->getPriceProductOfferFacade()->getProductOfferPrices($priceProductOfferCriteriaTransfer);
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
            $typePriceProductOdderId = explode(':', $requestedTypePriceProductOfferId);
            $typePriceProductOfferIds[$typePriceProductOdderId[0]] = (int)$typePriceProductOdderId[1];
        }

        return $typePriceProductOfferIds;
    }

    /**
     * @param string $priceTypeName
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function setPriceTypeToPriceProduct(
        string $priceTypeName,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        $priceTypes = $this->getFactory()->getPriceProductFacade()->getPriceTypeValues();
        foreach ($priceTypes as $priceTypeTransfer) {
            if ($priceTypeTransfer->getName() === $priceTypeName) {
                return $priceProductTransfer->setPriceType($priceTypeTransfer);
            }
        }

        return $priceProductTransfer;
    }
}
