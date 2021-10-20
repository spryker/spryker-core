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
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Exception\ProductOfferNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 */
class DeletePriceProductOfferController extends AbstractPriceProductOfferController
{
    /**
     * @var string
     */
    protected const POST_ACTION_TYPE_REFRESH_TABLE = 'refresh_table';

    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_SUCCESS = 'Success! The Price is deleted.';

    /**
     * @var string
     */
    protected const PARAM_PRODUCT_OFFER_ID = 'product-offer-id';

    /**
     * @var string
     */
    protected const PARAM_PRICE_PRODUCT_OFFER_IDS = 'price-product-offer-ids';

    /**
     * @var string
     */
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
            return $this->createErrorJsonResponse();
        }

        $response = $this->deleteProductOfferPrices($priceProductOfferCollectionTransfer, $quantity);

        if ($response) {
            return $response;
        }

        return $this->createSuccessJsonResponse();
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
     * @param array<int> $priceProductOfferIds
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

        /** @var \Generated\Shared\Transfer\ValidationErrorTransfer $validationErrorTransfer */
        $validationErrorTransfer = $validationResponseTransfer->getValidationErrors()->offsetGet(0);

        return $this->createErrorJsonResponse($validationErrorTransfer->getMessageOrFail());
    }
}
