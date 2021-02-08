<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductAbstractTableViewTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Laminas\Filter\StringToUpper;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class SavePriceProductAbstractController extends AbstractController
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $typePriceProductStoreIds = $this->parseTypePriceProductStoreIds($request->get(PriceProductAbstractTableViewTransfer::TYPE_PRICE_PRODUCT_STORE_IDS));
        $idProductAbstract = $request->get(PriceProductAbstractTableViewTransfer::ID_PRODUCT_ABSTRACT);

        $data = $this->getFactory()->getUtilEncodingService()->decodeJson((string)$request->getContent(), true)['data'];

        $priceProductTransfers = $this->getPriceProductTransfers($idProductAbstract, $typePriceProductStoreIds, $data);
        $priceProductTransfers = $this->getFactory()->createPriceProductMapper()->mapDataToPriceProductTransfers($data, $priceProductTransfers);

        $validationResponseTransfer = $this->getFactory()->getPriceProductFacade()->validatePrices($priceProductTransfers);
        if (!$validationResponseTransfer->getIsSuccess()) {
            return $this->getErrorJsonResponse($validationResponseTransfer);
        }

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $this->getFactory()->getPriceProductFacade()->persistPriceProductStore($priceProductTransfer);
        }

        return $this->getSuccessJsonResponse();
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
        $key = (string)key($data);
        $priceTypeName = (new StringToUpper())->filter((string)strstr($key, '[', true));
        $priceProductStoreIds = $this->getPriceProductStoreIds($key, $priceTypeName, $typePriceProductStoreIds);

        if (!$priceProductStoreIds) {
            $priceProductTransfers = new ArrayObject();
            $priceProductTransfer = $this->createNewPriceProduct($typePriceProductStoreIds, $priceTypeName, $idProductAbstract);
            $priceProductTransfers->append($priceProductTransfer);

            return $priceProductTransfers;
        }

        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())->setPriceProductStoreIds($priceProductStoreIds);

        $priceProductTransfers = $this->getFactory()
            ->getPriceProductFacade()
            ->findProductAbstractPricesWithoutPriceExtraction($idProductAbstract, $priceProductCriteriaTransfer);

        if ($priceProductTransfers && (isset($data[MoneyValueTransfer::STORE]) || isset($data[MoneyValueTransfer::CURRENCY]))) {
            $priceProductTransfers = $this->expandPriceProductTransfersWithTypes($idProductAbstract, $priceProductTransfers);
        }

        return new ArrayObject($priceProductTransfers);
    }

    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function expandPriceProductTransfersWithTypes(int $idProductAbstract, array $priceProductTransfers): array
    {
        $priceTypeIds = [];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceTypeIds[] = $priceProductTransfer->getFkPriceType();
        }

        foreach ($this->getFactory()->getPriceProductFacade()->getPriceTypeValues() as $priceTypeTransfer) {
            if (in_array($priceTypeTransfer->getIdPriceType(), $priceTypeIds)) {
                continue;
            }

            $moneyValueTransfer = $priceProductTransfers[0]->getMoneyValue();
            $priceProductTransfers[] = (new PriceProductTransfer())
                ->setFkPriceType($priceTypeTransfer->getIdPriceType())
                ->setPriceType($priceTypeTransfer)
                ->setPriceDimension(
                    (new PriceProductDimensionTransfer())->setType(static::PRICE_DIMENSION_TYPE_DEFAULT)
                )
                ->setIdProductAbstract($idProductAbstract)
                ->setMoneyValue(
                    (new MoneyValueTransfer())
                        ->setCurrency($moneyValueTransfer->getCurrency())
                        ->setFkStore($moneyValueTransfer->getFkStore())
                        ->setStore($moneyValueTransfer->getStore())
                        ->setFkCurrency($moneyValueTransfer->getFkCurrency())
                );
        }

        return $priceProductTransfers;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getSuccessJsonResponse(): JsonResponse
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
    protected function getErrorJsonResponse(ValidationResponseTransfer $validationResponseTransfer): JsonResponse
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

        foreach ($requestedTypePriceProductStoreIds as $key => $requestedTypePriceProductStoreId) {
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
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createNewPriceProduct(
        array $typePriceProductStoreIds,
        string $priceTypeName,
        int $idProductAbstract
    ): PriceProductTransfer {
        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())->setPriceProductStoreIds($typePriceProductStoreIds);

        $priceProductTransfers = $this->getFactory()
            ->getPriceProductFacade()
            ->findProductAbstractPricesWithoutPriceExtraction($idProductAbstract, $priceProductCriteriaTransfer);

        /** @var \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer */
        $priceProductTransfer = $priceProductTransfers[0];
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();

        $priceProductTransfer = (new PriceProductTransfer())
            ->setIdProductAbstract($idProductAbstract)
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
                return $priceProductTransfer->setPriceType($priceTypeTransfer)
                    ->setFkPriceType($priceTypeTransfer->getIdPriceType())
                    ->setPriceDimension(
                        (new PriceProductDimensionTransfer())->setType(static::PRICE_DIMENSION_TYPE_DEFAULT)
                    );
            }
        }

        return $priceProductTransfer;
    }
}
