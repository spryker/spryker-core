<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer;
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
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_SUCCESS = 'Product prices saved successfully.';

    /**
     * @var string
     */
    protected const REQUEST_BODY_CONTENT_KEY_DATA = 'data';

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    abstract protected function expandPriceProductTransfersWithProductId(ArrayObject $priceProductTransfers, Request $request): ArrayObject;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    abstract protected function findPriceProductTransfers(Request $request): array;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    abstract protected function isProductOwnedByCurrentMerchant(Request $request): bool;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Spryker\Zed\ProductMerchantPortalGui\Communication\Exception\WrongRequestBodyContentException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        if (!$this->isProductOwnedByCurrentMerchant($request)) {
            return new JsonResponse(['success' => false]);
        }

        $requestBodyContent = $this->getFactory()
            ->getUtilEncodingService()
            ->decodeJson((string)$request->getContent(), true);

        if (!isset($requestBodyContent[static::REQUEST_BODY_CONTENT_KEY_DATA])) {
            throw new WrongRequestBodyContentException(static::REQUEST_BODY_CONTENT_KEY_DATA);
        }

        $data = $requestBodyContent[static::REQUEST_BODY_CONTENT_KEY_DATA];
        $volumeQuantity = (int)$request->get(PriceProductTableViewTransfer::VOLUME_QUANTITY, 1);

        $priceProductTransfers = $this->findPriceProductTransfers($request);

        $mappedPriceProductTransfers = $this->getFactory()
            ->createSingleFieldPriceProductMapper()
            ->mapPriceProductTransfers($data, $volumeQuantity, new ArrayObject($this->clonePriceProductTransfers($priceProductTransfers)));

        $mappedPriceProductTransfers = $this->expandPriceProductTransfersWithProductId($mappedPriceProductTransfers, $request);

        $validationResponseTransfer = $this->getFactory()
            ->getPriceProductFacade()
            ->validatePrices($mappedPriceProductTransfers);

        if (!$validationResponseTransfer->getIsSuccess()) {
            return $this->createErrorJsonResponse($validationResponseTransfer);
        }

        foreach ($mappedPriceProductTransfers as $priceProductTransfer) {
            $this->getFactory()->getPriceProductFacade()->persistPriceProductStore($priceProductTransfer);
        }

        $priceProductTransfersToRemove = $this->getPriceProductTransfersToRemove($priceProductTransfers, $mappedPriceProductTransfers);

        if (!$priceProductTransfersToRemove) {
            return $this->createSuccessJsonResponse();
        }

        $priceProductCollectionDeleteCriteriaTransfer = $this->getFactory()
            ->createPriceProductMapper()
            ->mapPriceProductTransfersToPriceProductCollectionDeleteCriteriaTransfer(
                $priceProductTransfersToRemove,
                new PriceProductCollectionDeleteCriteriaTransfer(),
            );
        $this->getFactory()->getPriceProductFacade()->deletePriceProductCollection($priceProductCollectionDeleteCriteriaTransfer);

        return $this->createSuccessJsonResponse();
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function clonePriceProductTransfers(array $priceProductTransfer): array
    {
        return unserialize(serialize($priceProductTransfer));
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\PriceProductTransfer> $mappedPriceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function getPriceProductTransfersToRemove(array $priceProductTransfers, ArrayObject $mappedPriceProductTransfers): array
    {
        $priceProductTransfersToRemove = [];

        $priceProductService = $this->getFactory()->getPriceProductService();
        $mappedPriceProductGroupKeys = $this->getPriceProductGroupKeys($mappedPriceProductTransfers->getArrayCopy());

        foreach ($priceProductTransfers as $priceProductTransfer) {
            if (!in_array($priceProductService->buildPriceProductGroupKey($priceProductTransfer), $mappedPriceProductGroupKeys)) {
                $priceProductTransfersToRemove[] = $priceProductTransfer;
            }
        }

        return $priceProductTransfersToRemove;
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
            ->addActionRefreshTable()
            ->createResponse();

        return new JsonResponse($zedUiFormResponseTransfer->toArray());
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<string>
     */
    protected function getPriceProductGroupKeys(array $priceProductTransfers): array
    {
        $priceProductService = $this->getFactory()->getPriceProductService();
        $priceProductGroupKeys = [];
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceProductGroupKeys[] = $priceProductService->buildPriceProductGroupKey($priceProductTransfer);
        }

        return $priceProductGroupKeys;
    }
}
