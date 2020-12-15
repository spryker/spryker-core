<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Action\UpdateProductOffer;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Action\ActionInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface;
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
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface $priceProductOfferFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface $priceProductOfferFacade,
        ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService,
        ProductOfferMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
    ) {
        $this->priceProductOfferFacade = $priceProductOfferFacade;
        $this->utilEncodingService = $utilEncodingService;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function execute(Request $request): JsonResponse
    {
        $typePriceProductOfferIds = $this->parseTypePriceProductOfferIds($request->get(static::PARAM_TYPE_PRICE_PRODUCT_OFFER_IDS));

        $data = $this->utilEncodingService->decodeJson((string)$request->getContent(), true)['data'];

        $priceProductTransfers = $this->getPriceProductTransfers($typePriceProductOfferIds, $data);
        $priceProductTransfers = $this->mapDataToPriceProductTransfers($data, $priceProductTransfers);

        $validationResponseTransfer = $this->priceProductOfferFacade->validateProductOfferPrices($priceProductTransfers);
        if (!$validationResponseTransfer->getIsSuccess()) {
            return $this->errorJsonResponse($validationResponseTransfer);
        }

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $productOfferTransfer = (new ProductOfferTransfer())->setIdProductConcrete((int)$priceProductTransfer->getIdProduct())
                ->setIdProductOffer((int)$priceProductTransfer->getPriceDimension()->getIdProductOffer())
                ->addPrice($priceProductTransfer);
            $this->priceProductOfferFacade->saveProductOfferPrices($productOfferTransfer);
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
        $key = (string)key($data);
        if (strpos($key, '[') !== false) {
            $priceProductOfferIds[] = $typePriceProductOfferIds[mb_strtoupper((string)strstr($key, '[', true))];
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
    protected function mapDataToPriceProductTransfers(array $data, ArrayObject $priceProductTransfers): ArrayObject
    {
        /** @var \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer */
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $this->mapDataToMoneyValueTransfer($data, $priceProductTransfer->getMoneyValueOrFail());
        }

        return $priceProductTransfers;
    }

    /**
     * @phpstan-param array<mixed> $data
     *
     * @param array $data
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function mapDataToMoneyValueTransfer(array $data, MoneyValueTransfer $moneyValueTransfer): MoneyValueTransfer
    {
        foreach ($data as $key => $value) {
            if (strpos($key, MoneyValueTransfer::NET_AMOUNT) !== false) {
                $value = $this->moneyFacade->convertDecimalToInteger((float)$value);
                $moneyValueTransfer->setNetAmount($value);

                continue;
            }

            if (strpos($key, MoneyValueTransfer::GROSS_AMOUNT) !== false) {
                $value = $this->moneyFacade->convertDecimalToInteger((float)$value);
                $moneyValueTransfer->setGrossAmount($value);

                continue;
            }

            if ($key === MoneyValueTransfer::STORE) {
                $value = (int)$value;
                $moneyValueTransfer->setFkStore($value);
                $moneyValueTransfer->setStore((new StoreTransfer())->setIdStore($value));

                continue;
            }

            if ($key === MoneyValueTransfer::CURRENCY) {
                $value = (int)$value;
                $moneyValueTransfer->setFkCurrency($value);

                continue;
            }
        }

        return $moneyValueTransfer;
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
}
