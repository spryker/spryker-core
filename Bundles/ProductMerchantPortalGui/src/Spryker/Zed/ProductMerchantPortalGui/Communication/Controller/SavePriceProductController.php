<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Laminas\Filter\StringToUpper;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\WrongRequestBodyContentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
abstract class SavePriceProductController extends AbstractController
{
    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DIMENSION_DEFAULT
     */
    protected const PRICE_DIMENSION_TYPE_DEFAULT = 'PRICE_DIMENSION_DEFAULT';

    protected const RESPONSE_MESSAGE_SUCCESS = 'Product prices saved successfully.';

    protected const RESPONSE_KEY_POST_ACTIONS = 'postActions';
    protected const RESPONSE_KEY_NOTIFICATIONS = 'notifications';
    protected const RESPONSE_KEY_TYPE = 'type';
    protected const RESPONSE_KEY_MESSAGE = 'message';

    protected const RESPONSE_TYPE_REFRESH_TABLE = 'refresh_table';
    protected const RESPONSE_TYPE_SUCCESS = 'success';
    protected const RESPONSE_TYPE_ERROR = 'error';

    protected const REQUEST_BODY_CONTENT_KEY_DATA = 'data';

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    abstract protected function expandPriceProductTransfersWithProductId(ArrayObject $priceProductTransfers, Request $request): ArrayObject;

    /**
     * @param int[] $priceProductStoreIds
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    abstract protected function findPriceProductTransfers(array $priceProductStoreIds, Request $request): array;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\WrongRequestBodyContentException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $typePriceProductStoreIds = $this->parseTypePriceProductStoreIds($request->get(PriceProductTableViewTransfer::TYPE_PRICE_PRODUCT_STORE_IDS));

        $requestBodyContent = $this->getFactory()->getUtilEncodingService()->decodeJson((string)$request->getContent(), true);

        if (!isset($requestBodyContent[static::REQUEST_BODY_CONTENT_KEY_DATA])) {
            throw new WrongRequestBodyContentException(static::REQUEST_BODY_CONTENT_KEY_DATA);
        }

        $data = $requestBodyContent[static::REQUEST_BODY_CONTENT_KEY_DATA];

        $priceProductTransfers = $this->getPriceProductTransfers($request, $typePriceProductStoreIds, $data);
        $priceProductTransfers = $this->expandPriceProductTransfersWithProductId($priceProductTransfers, $request);

        $validationResponseTransfer = $this->getFactory()->getPriceProductFacade()->validatePrices($priceProductTransfers);
        if (!$validationResponseTransfer->getIsSuccess()) {
            return $this->createErrorJsonResponse($validationResponseTransfer);
        }

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $this->getFactory()->getPriceProductFacade()->persistPriceProductStore($priceProductTransfer);
        }

        return $this->createSuccessJsonResponse();
    }

    /**
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int[] $typePriceProductStoreIds
     * @param string[] $data
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function getPriceProductTransfers(
        Request $request,
        array $typePriceProductStoreIds,
        array $data
    ): ArrayObject {
        $key = (string)key($data);
        $priceTypeName = (new StringToUpper())->filter((string)strstr($key, '[', true));
        $priceProductStoreIds = $this->getPriceProductStoreIds($key, $priceTypeName, $typePriceProductStoreIds);

        if (!$priceProductStoreIds) {
            $priceProductTransfers = new ArrayObject();
            $priceProductTransfer = $this->createNewPriceProduct($typePriceProductStoreIds, $priceTypeName, $request);
            $priceProductTransfers->append($priceProductTransfer);

            return $priceProductTransfers;
        }

        $priceProductTransfers = $this->findPriceProductTransfers($priceProductStoreIds, $request);

        if ($priceProductTransfers && (isset($data[MoneyValueTransfer::STORE]) || isset($data[MoneyValueTransfer::CURRENCY]))) {
            $priceProductTransfers = $this->expandPriceProductTransfersWithTypes($priceProductTransfers);
        }

        $priceProductTransfers = $this->getFactory()->createPriceProductMapper()->mapDataToPriceProductTransfers(
            $data,
            new ArrayObject($priceProductTransfers)
        );

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function expandPriceProductTransfersWithTypes(array $priceProductTransfers): array
    {
        $priceTypeIds = [];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceTypeIds[] = $priceProductTransfer->getFkPriceType();
        }

        $moneyValueTransfer = $priceProductTransfers[0]->getMoneyValueOrFail();

        foreach ($this->getFactory()->getPriceProductFacade()->getPriceTypeValues() as $priceTypeTransfer) {
            if (in_array($priceTypeTransfer->getIdPriceType(), $priceTypeIds)) {
                continue;
            }

            $priceProductTransfers[] = (new PriceProductTransfer())
                ->setFkPriceType($priceTypeTransfer->getIdPriceType())
                ->setPriceType($priceTypeTransfer)
                ->setPriceDimension(
                    (new PriceProductDimensionTransfer())->setType(static::PRICE_DIMENSION_TYPE_DEFAULT)
                )
                ->setMoneyValue($this->recreateMoneyValueTransfer($moneyValueTransfer));
        }

        return $priceProductTransfers;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function createSuccessJsonResponse(): JsonResponse
    {
        $response = [
            static::RESPONSE_KEY_NOTIFICATIONS => [
                [
                    static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_SUCCESS,
                    static::RESPONSE_KEY_MESSAGE => static::RESPONSE_MESSAGE_SUCCESS,
                ],
            ],
            static::RESPONSE_KEY_POST_ACTIONS => [
                [
                    static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_REFRESH_TABLE,
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
    protected function createErrorJsonResponse(ValidationResponseTransfer $validationResponseTransfer): JsonResponse
    {
        $notifications = [];
        /** @var \Generated\Shared\Transfer\ValidationErrorTransfer $validationErrorTransfer */
        $validationErrorTransfer = $validationResponseTransfer->getValidationErrors()->offsetGet(0);
        $notifications[] = [
            static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_ERROR,
            static::RESPONSE_KEY_MESSAGE => $validationErrorTransfer->getMessage(),
        ];
        $response = [
            static::RESPONSE_KEY_NOTIFICATIONS => $notifications,
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

        foreach ($requestedTypePriceProductStoreIds as $requestedTypePriceProductStoreId) {
            $typePriceProductStoreId = explode(':', $requestedTypePriceProductStoreId);
            $typePriceProductStoreIds[$typePriceProductStoreId[0]] = (int)$typePriceProductStoreId[1];
        }

        return $typePriceProductStoreIds;
    }

    /**
     * @param string $key
     * @param string $priceTypeName
     * @param int[] $typePriceProductStoreIds
     *
     * @return int[]
     */
    protected function getPriceProductStoreIds(string $key, string $priceTypeName, array $typePriceProductStoreIds): array
    {
        $priceProductStoreIds = [];
        if (strpos($key, '[') !== false) {
            if (array_key_exists($priceTypeName, $typePriceProductStoreIds)) {
                $priceProductStoreIds[] = $typePriceProductStoreIds[$priceTypeName];
            }

            return $priceProductStoreIds;
        }

        return $typePriceProductStoreIds;
    }

    /**
     * @param int[] $typePriceProductStoreIds
     * @param string $priceTypeName
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createNewPriceProduct(
        array $typePriceProductStoreIds,
        string $priceTypeName,
        Request $request
    ): PriceProductTransfer {
        $priceProductTransfers = $this->findPriceProductTransfers($typePriceProductStoreIds, $request);

        $priceProductTransfer = $priceProductTransfers[0];
        $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();

        $priceProductTransfer = (new PriceProductTransfer())
            ->setMoneyValue($this->recreateMoneyValueTransfer($moneyValueTransfer));

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
                return $priceProductTransfer->setPriceType($priceTypeTransfer)
                    ->setFkPriceType($priceTypeTransfer->getIdPriceType())
                    ->setPriceDimension(
                        (new PriceProductDimensionTransfer())->setType(static::PRICE_DIMENSION_TYPE_DEFAULT)
                    );
            }
        }

        return $priceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function recreateMoneyValueTransfer(MoneyValueTransfer $moneyValueTransfer): MoneyValueTransfer
    {
        return (new MoneyValueTransfer())
            ->setCurrency($moneyValueTransfer->getCurrency())
            ->setFkStore($moneyValueTransfer->getFkStore())
            ->setStore($moneyValueTransfer->getStore())
            ->setFkCurrency($moneyValueTransfer->getFkCurrency());
    }
}
