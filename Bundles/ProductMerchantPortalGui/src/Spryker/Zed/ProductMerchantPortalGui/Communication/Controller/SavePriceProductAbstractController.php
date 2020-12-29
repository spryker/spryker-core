<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductAbstractTableViewTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
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
        $priceProductStoreIds = $this->getPriceProductStoreIds($data, $typePriceProductStoreIds);

        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())->setPriceProductStoreIds($priceProductStoreIds);

        return new ArrayObject($this->getFactory()
            ->getPriceProductFacade()
            ->findProductAbstractPricesWithoutPriceExtraction($idProductAbstract, $priceProductCriteriaTransfer));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getSuccessJsonResponse(): JsonResponse
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
    protected function getErrorJsonResponse(ValidationResponseTransfer $validationResponseTransfer): JsonResponse
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
