<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Action;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductAbstractTableViewTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SavePricesAction implements ActionInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade,
        ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->moneyFacade = $moneyFacade;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function execute(Request $request): JsonResponse
    {
        $typePriceProductStoreIds = $this->parseTypePriceProductStoreIds($request->get(PriceProductAbstractTableViewTransfer::TYPE_PRICE_PRODUCT_STORE_IDS));
        $idProductAbstract = $request->get(PriceProductAbstractTableViewTransfer::ID_PRODUCT_ABSTRACT);

        $data = $this->utilEncodingService->decodeJson((string)$request->getContent(), true)['data'];

        $priceProductTransfers = $this->getPriceProductTransfers($idProductAbstract, $typePriceProductStoreIds, $data);
        $priceProductTransfers = $this->mapDataToPriceProductTransfers($data, $priceProductTransfers);

        $validationResponseTransfer = $this->priceProductFacade->validatePrices($priceProductTransfers);
        if (!$validationResponseTransfer->getIsSuccess()) {
            return $this->errorJsonResponse($validationResponseTransfer);
        }

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $this->priceProductFacade->persistPriceProductStore($priceProductTransfer);
        }

        return $this->successJsonResponse();
    }

    /**
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param int $idProductAbstract
     * @param int[] $typePriceProductStoreIds
     * @param string[] $data
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function getPriceProductTransfers(
        int $idProductAbstract,
        array $typePriceProductStoreIds,
        array $data
    ): ArrayObject {
        $priceProductStoreIds = $this->getPriceProductStoreIds($data, $typePriceProductStoreIds);

        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())->setPriceProductStoreIds($priceProductStoreIds);

        return new ArrayObject($this->priceProductFacade
            ->findProductAbstractPricesWithoutPriceExtraction($idProductAbstract, $priceProductCriteriaTransfer));
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
                    'type' => 'success',
                    'message' => 'Product prices saved successfully.',
                ],
            ],
            'postActions' => [
                [
                    'type' => 'refresh_table',
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
            'type' => 'error',
            'message' => $validationErrorTransfer->getMessage(),
        ];
        $response = [
            'notifications' => $notifications,
        ];

        return new JsonResponse($response);
    }

    /**
     * @param string $requestedTypePriceProductStoreIds
     *
     * @return int[]
     */
    protected function parseTypePriceProductStoreIds(string $requestedTypePriceProductStoreIds): array
    {
        $requestedTypePriceProductStoreIds = explode(',', $requestedTypePriceProductStoreIds);
        $typePriceProductStoreIds = [];

        foreach ($requestedTypePriceProductStoreIds as $key => $requestedTypePriceProductStoreId) {
            $typePriceProductStoreId = explode(':', $requestedTypePriceProductStoreId);
            $typePriceProductStoreIds[$typePriceProductStoreId[0]] = (int)$typePriceProductStoreId[1];
        }

        return $typePriceProductStoreIds;
    }

    /**
     * @param string[] $data
     * @param int[] $typePriceProductStoreIds
     *
     * @return int[]
     */
    protected function getPriceProductStoreIds(array $data, array $typePriceProductStoreIds): array
    {
        $priceProductStoreIds = [];
        $key = (string)key($data);

        if (strpos($key, '[') !== false) {
            $priceProductStoreIds[] = $typePriceProductStoreIds[mb_strtoupper((string)strstr($key, '[', true))];

            return $priceProductStoreIds;
        }

        return $typePriceProductStoreIds;
    }
}
