<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Action\UpdateProductOffer;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductOfferCollectionValidationResponseTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Action\ActionInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SavePricesAction implements ActionInterface
{
    protected const PARAM_ID_PRODUCT_OFFER = 'product-offer-id';
    protected const PARAM_TYPE_PRICE_PRODUCT_OFFER_IDS = 'type-price-product-offer-ids';
    protected const PARAM_NET = 'netAmount';
    protected const PARAM_GROSS = 'grossAmount';
    protected const PARAM_STORE = 'store';
    protected const PARAM_CURRENCY = 'currency';

    protected const NOTIFICATION_TYPE_SUCCESS = 'success';
    protected const NOTIFICATION_TYPE_ERROR = 'error';
    protected const NOTIFICATION_SUCCESS_MESSAGE = 'Offer prices saved successfuly.';

    protected const POST_ACTION_REFRESH_TABLE = 'refresh_table';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface
     */
    protected $priceProductOfferFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface $priceProductOfferFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface $priceProductOfferFacade,
        ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->priceProductOfferFacade = $priceProductOfferFacade;
        $this->priceProductFacade = $priceProductFacade;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function execute(Request $request): JsonResponse
    {
        $requestedTypePriceProductOfferIds = $request->get(static::PARAM_TYPE_PRICE_PRODUCT_OFFER_IDS);
        $requestedTypePriceProductOfferIds = str_replace(['[', ']'], '', $requestedTypePriceProductOfferIds);
        $requestedTypePriceProductOfferIds = explode(',', $requestedTypePriceProductOfferIds);
        $typePriceProductOfferIds = [];

        foreach ($requestedTypePriceProductOfferIds as $key => $requestedTypePriceProductOfferId) {
            $typePriceProductOdderId = explode(':', $requestedTypePriceProductOfferId);
            $typePriceProductOfferIds[$typePriceProductOdderId[0]] = (int)$typePriceProductOdderId[1];
        }

        $data = $this->utilEncodingService->decodeJson((string)$request->getContent(), true)['data'];

        $priceProductTransfers = $this->getPriceProductTransfers($typePriceProductOfferIds, $data);
        $priceProductTransfers = $this->mapDataToProductOfferTransfers($data, $priceProductTransfers);
        $collectionValidationResponseTransfer = $this->priceProductOfferFacade->validateProductOfferPrices($priceProductTransfers);
        if (!$collectionValidationResponseTransfer->getIsSuccessful()) {
            return $this->errorJsonResponse($collectionValidationResponseTransfer);
        }

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $this->priceProductFacade->persistPriceProductStore($priceProductTransfer);
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
    protected function getPriceProductTransfers(array $typePriceProductOfferIds, array $data): ArrayObject
    {
        $priceProductOfferIds = [];
        if (stristr((string)key($data), '[', true) !== false) {
            $priceProductOfferIds[] = $typePriceProductOfferIds[mb_strtoupper(stristr((string)key($data), '[', true))];
        } else {
            $priceProductOfferIds = $typePriceProductOfferIds;
        }

        $priceProductOfferCriteriaTransfer = new PriceProductOfferCriteriaTransfer();
        $priceProductOfferCriteriaTransfer->setPriceProductOfferIds($priceProductOfferIds);

        return $this->priceProductOfferFacade->getProductOfferPrices($priceProductOfferCriteriaTransfer);
    }

    /**
     * @phpstan-param array<mixed> $data
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param array $data
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function mapDataToProductOfferTransfers(array $data, ArrayObject $priceProductTransfers): ArrayObject
    {
        $key = (string)key($data);
        $value = $data[$key] === '' ? null : (int)$data[$key];
        $key = str_replace(']', '', $key);
        $key = explode('[', $key);
        $key = $key[count($key) - 1];

        /** @var \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer */
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $this->mapDataToProductOfferTransfer($key, $value, $priceProductTransfer->getMoneyValueOrFail());
        }

        return $priceProductTransfers;
    }

    /**
     * @param string $dataType
     * @param int|null $value
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function mapDataToProductOfferTransfer(string $dataType, ?int $value, MoneyValueTransfer $moneyValueTransfer): MoneyValueTransfer
    {
        switch ($dataType) {
            case static::PARAM_NET:
                $moneyValueTransfer->setNetAmount($value);

                break;
            case static::PARAM_GROSS:
                $moneyValueTransfer->setGrossAmount($value);

                break;
            case static::PARAM_STORE:
                $moneyValueTransfer->setFkStore($value);
                $moneyValueTransfer->setStore((new StoreTransfer())->setIdStore($value));

                break;
            case static::PARAM_CURRENCY:
                $moneyValueTransfer->setFkCurrency($value);

                break;
        }

        return $moneyValueTransfer;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function successJsonResponse(): JsonResponse
    {
        $response = [
            'notifications' => [[
                'type' => static::NOTIFICATION_TYPE_SUCCESS,
                'message' => static::NOTIFICATION_SUCCESS_MESSAGE,
            ]],
            'postActions' => [['type' => static::POST_ACTION_REFRESH_TABLE]],
        ];

        return new JsonResponse($response);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionValidationResponseTransfer $validationResponseTransfer
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function errorJsonResponse(PriceProductOfferCollectionValidationResponseTransfer $validationResponseTransfer): JsonResponse
    {
        $notificaions = [];
        foreach ($validationResponseTransfer->getErrors() as $validationErrorTransfers) {
            foreach ($validationErrorTransfers->getValidationErrors() as $validationErrorTransfer) {
                $notificaions[] = [
                    'type' => static::NOTIFICATION_TYPE_ERROR,
                    'message' => $validationErrorTransfer->getMessage(),
                ];
            }
        }
        $response = [
            'notifications' => $notificaions,
        ];

        return new JsonResponse($response);
    }
}
