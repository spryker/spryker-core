<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\WrongRequestBodyContentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
abstract class AbstractSavePriceProductController extends AbstractController
{
    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DIMENSION_DEFAULT
     */
    protected const PRICE_DIMENSION_TYPE_DEFAULT = 'PRICE_DIMENSION_DEFAULT';

    protected const RESPONSE_NOTIFICATION_MESSAGE_SUCCESS = 'Product prices saved successfully.';

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
        $typePriceProductStoreIds = $this->getFactory()
            ->getUtilEncodingService()
            ->decodeJson((string)$request->get(PriceProductTableViewTransfer::TYPE_PRICE_PRODUCT_STORE_IDS), true);

        $requestBodyContent = $this->getFactory()
            ->getUtilEncodingService()
            ->decodeJson((string)$request->getContent(), true);

        if (!isset($requestBodyContent[static::REQUEST_BODY_CONTENT_KEY_DATA])) {
            throw new WrongRequestBodyContentException(static::REQUEST_BODY_CONTENT_KEY_DATA);
        }

        $data = $requestBodyContent[static::REQUEST_BODY_CONTENT_KEY_DATA];
        $volumeQuantity = (int)$request->get(PriceProductTableViewTransfer::VOLUME_QUANTITY);

        $priceProductTransfers = $this->findPriceProductTransfers($typePriceProductStoreIds, $request);
        $priceProductTransfers = $this->getFactory()
            ->createSingleFieldPriceProductMapper()
            ->mapPriceProductTransfers($data, $volumeQuantity, new ArrayObject($priceProductTransfers));
        $priceProductTransfers = $this->expandPriceProductTransfersWithProductId($priceProductTransfers, $request);

        $validationResponseTransfer = $this->getFactory()
            ->getPriceProductFacade()
            ->validatePrices($priceProductTransfers);

        if (!$validationResponseTransfer->getIsSuccess()) {
            return $this->createErrorJsonResponse($validationResponseTransfer);
        }

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $this->getFactory()->getPriceProductFacade()->persistPriceProductStore($priceProductTransfer);
        }

        return $this->createSuccessJsonResponse();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function createSuccessJsonResponse(): JsonResponse
    {
        $zedUiFormResponseTransfer = $this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addSuccessNotification(static::RESPONSE_NOTIFICATION_MESSAGE_SUCCESS)
            ->addActionRefreshTable()
            ->createResponse();

        return new JsonResponse($zedUiFormResponseTransfer->toArray());
    }

    /**
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function createErrorJsonResponse(ValidationResponseTransfer $validationResponseTransfer): JsonResponse
    {
        /** @var \Generated\Shared\Transfer\ValidationErrorTransfer $validationErrorTransfer */
        $validationErrorTransfer = $validationResponseTransfer->getValidationErrors()->offsetGet(0);

        $zedUiFormResponseTransfer = $this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addErrorNotification($validationErrorTransfer->getMessageOrFail())
            ->createResponse();

        return new JsonResponse($zedUiFormResponseTransfer->toArray());
    }
}
