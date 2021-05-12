<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
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

        $priceProductTransfers = $this->getPriceProductTransfers($typePriceProductOfferIds, $requestData);
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
     * @param int[] $typePriceProductOfferIds
     * @param string[] $data
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function getPriceProductTransfers(
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
            $priceProductTransfer = $this->createNewPriceForProductOffer($typePriceProductOfferIds, $priceTypeName);
            $priceProductTransfers->append($priceProductTransfer);

            return $priceProductTransfers;
        }

        $priceProductOfferCriteriaTransfer = new PriceProductOfferCriteriaTransfer();
        $priceProductOfferCriteriaTransfer->setPriceProductOfferIds($priceProductOfferIds);

        $priceProductTransfers = $this->getFactory()->getPriceProductOfferFacade()->getProductOfferPrices($priceProductOfferCriteriaTransfer);

        if ($priceProductTransfers->count() && (isset($data[MoneyValueTransfer::STORE]) || isset($data[MoneyValueTransfer::CURRENCY]))) {
            $priceProductTransfers = $this->expandPriceProductTransfersWithTypes($priceProductTransfers);
        }

        return $priceProductTransfers;
    }

    /**
     * @phpstan-param ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @phpstan-return ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function expandPriceProductTransfersWithTypes(ArrayObject $priceProductTransfers): ArrayObject
    {
        $priceTypeIds = [];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceTypeIds[] = $priceProductTransfer->getPriceTypeOrFail()->getIdPriceType();
        }

        if (!isset($priceProductTransfers[0])) {
            return $priceProductTransfers;
        }

        foreach ($this->getFactory()->getPriceProductFacade()->getPriceTypeValues() as $priceTypeTransfer) {
            if (in_array($priceTypeTransfer->getIdPriceType(), $priceTypeIds)) {
                continue;
            }

            $moneyValueTransfer = $priceProductTransfers[0]->getMoneyValueOrFail();
            $priceProductTransfers->append((new PriceProductTransfer())
                ->setPriceType($priceTypeTransfer)
                ->setIdProduct($priceProductTransfers->getIterator()->current()->getIdProduct())
                ->setPriceDimension(
                    (new PriceProductDimensionTransfer())
                        ->setIdProductOffer($priceProductTransfers->getIterator()->current()->getPriceDimensionOrFail()->getIdProductOffer())
                )
                ->setMoneyValue(
                    (new MoneyValueTransfer())
                        ->setCurrency($moneyValueTransfer->getCurrency())
                        ->setFkStore($moneyValueTransfer->getFkStore())
                        ->setStore($moneyValueTransfer->getStore())
                        ->setFkCurrency($moneyValueTransfer->getFkCurrency())
                ));
        }

        return $priceProductTransfers;
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
     * @param int[] $typePriceProductOfferIds
     * @param string $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createNewPriceForProductOffer(array $typePriceProductOfferIds, string $priceTypeName): PriceProductTransfer
    {
        $priceProductOfferCriteriaTransfer = new PriceProductOfferCriteriaTransfer();
        $priceProductOfferCriteriaTransfer->setPriceProductOfferIds($typePriceProductOfferIds);

        $offerPriceProductTransfers = $this->getFactory()
            ->getPriceProductOfferFacade()
            ->getProductOfferPrices($priceProductOfferCriteriaTransfer);

        /** @var \Generated\Shared\Transfer\PriceProductTransfer $offerPriceProductTransfer */
        $offerPriceProductTransfer = $offerPriceProductTransfers->getIterator()->current();
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $offerPriceProductTransfer->getMoneyValue();
        /** @var \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer */
        $priceProductDimensionTransfer = $offerPriceProductTransfer->getPriceDimension();

        $priceProductTransfer = (new PriceProductTransfer())
            ->setIdProduct($offerPriceProductTransfer->getIdProduct())
            ->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setIdProductOffer($priceProductDimensionTransfer->getIdProductOffer())
            )
            ->setMoneyValue(
                (new MoneyValueTransfer())
                    ->setCurrency($moneyValueTransfer->getCurrency())
                    ->setFkStore($moneyValueTransfer->getFkStore())
                    ->setStore($moneyValueTransfer->getStore())
                    ->setFkCurrency($moneyValueTransfer->getFkCurrency())
            );

        return $this->setPriceTypeToPriceProduct($priceTypeName, $priceProductTransfer);
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
