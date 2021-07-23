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
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Exception\ProductOfferNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 */
class DeletePriceProductOfferController extends AbstractController
{
    protected const POST_ACTION_TYPE_REFRESH_TABLE = 'refresh_table';
    protected const NOTIFICATION_TYPE_SUCCESS = 'success';
    protected const NOTIFICATION_TYPE_ERROR = 'error';
    protected const SUCCESS_MESSAGE = 'Success! The Price is deleted.';
    protected const ERROR_MESSAGE = 'Something went wrong, please try again.';

    protected const PARAM_PRODUCT_OFFER_ID = 'product-offer-id';
    protected const PARAM_PRICE_PRODUCT_OFFER_IDS = 'price-product-offer-ids';
    protected const PARAM_QUANTITY = 'quantity';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $productOfferId = $this->castId($request->get(static::PARAM_PRODUCT_OFFER_ID));
        $quantity = $this->castId($request->get(static::PARAM_QUANTITY));
        $priceProductOfferIds = array_map(
            'intval',
            $this->getFactory()->getUtilEncodingService()->decodeJson($request->get(static::PARAM_PRICE_PRODUCT_OFFER_IDS), true)
        );

        $priceProductOfferCollectionTransfer = $this->createPriceProductOfferCollectionTransferByPriceProductOfferIds(
            $productOfferId,
            $priceProductOfferIds
        );

        if (!$this->validatePriceProductOfferIds($priceProductOfferCollectionTransfer)) {
            $responseData['notifications'][] = [
                'type' => static::NOTIFICATION_TYPE_ERROR,
                'message' => static::ERROR_MESSAGE,
            ];

            return new JsonResponse($responseData);
        }

        $response = $this->deleteProductOfferPrices($priceProductOfferCollectionTransfer, $quantity);
        if ($response) {
            return $response;
        }

        $responseData = [
            'postActions' => [
                [
                    'type' => static::POST_ACTION_TYPE_REFRESH_TABLE,
                ],
            ],
            'notifications' => [
                [
                    'type' => static::NOTIFICATION_TYPE_SUCCESS,
                    'message' => static::SUCCESS_MESSAGE,
                ],
            ],
        ];

        return new JsonResponse($responseData);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     *
     * @return bool
     */
    protected function validatePriceProductOfferIds(PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer): bool
    {
        $constraintViolationList = $this->getFactory()
            ->getValidationAdapter()
            ->createValidator()
            ->validate(
                $priceProductOfferCollectionTransfer,
                $this->getFactory()->createValidProductOfferPriceIdsOwnByMerchantConstraint()
            );

        return $constraintViolationList->count() === 0;
    }

    /**
     * @param int $idProductOffer
     * @param int[] $priceProductOfferIds
     *
     * @throws \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Exception\ProductOfferNotFoundException
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer
     */
    protected function createPriceProductOfferCollectionTransferByPriceProductOfferIds(
        int $idProductOffer,
        array $priceProductOfferIds
    ): PriceProductOfferCollectionTransfer {
        $priceProductOfferCollectionTransfer = new PriceProductOfferCollectionTransfer();

        $currentMerchantReference = $this->getFactory()->getMerchantUserFacade()
            ->getCurrentMerchantUser()
            ->getMerchantOrFail()
            ->getMerchantReferenceOrFail();

        $productOfferCriteriaTransfer = (new ProductOfferCriteriaTransfer())
            ->setIdProductOffer($idProductOffer)
            ->addMerchantReference($currentMerchantReference);

        $productOfferTransfer = $this->getFactory()
            ->getProductOfferFacade()
            ->findOne($productOfferCriteriaTransfer);

        if (!$productOfferTransfer) {
            throw new ProductOfferNotFoundException();
        }

        $productOfferTransfer->setPrices(
            (new ArrayObject(
                $this->getFactory()->createPriceProductFilter()->filterPriceProductTransfers(
                    $productOfferTransfer->getPrices()->getArrayCopy(),
                    (new PriceProductOfferCriteriaTransfer())
                        ->addVolumeQuantity(1)
                        ->setPriceProductOfferIds($priceProductOfferIds)
                )
            ))
        );

        $idConcreteProduct = $this->getFactory()
            ->getProductFacade()
            ->findProductConcreteIdBySku($productOfferTransfer->getConcreteSkuOrFail());

        $productOfferTransfer->setIdProductConcrete($idConcreteProduct);

        foreach ($priceProductOfferIds as $idPriceProductOffer) {
            $priceProductOfferTransfer = (new PriceProductOfferTransfer())
                ->setIdPriceProductOffer($idPriceProductOffer)
                ->setProductOffer($productOfferTransfer);

            $priceProductOfferCollectionTransfer->addPriceProductOffer($priceProductOfferTransfer);
        }

        return $priceProductOfferCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     * @param int $quantity
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|null
     */
    protected function deleteProductOfferPrices(
        PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer,
        int $quantity
    ): ?JsonResponse {
        $validationResponseTransfer = $this->getFactory()
            ->createPriceDeleter()
            ->deletePriceByQuantity($priceProductOfferCollectionTransfer, $quantity);

        if ($validationResponseTransfer->getIsSuccess()) {
            return null;
        }

        $responseData = [];

        foreach ($validationResponseTransfer->getValidationErrors() as $validationErrorTransfer) {
            $responseData['notifications'][] = [
                'type' => static::NOTIFICATION_TYPE_ERROR,
                'message' => $validationErrorTransfer->getMessage(),
            ];

            break;
        }

        return new JsonResponse($responseData);
    }
}
