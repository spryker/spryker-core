<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
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

    protected const PARAM_PRICE_PRODUCT_OFFER_IDS = 'price-product-offer-ids';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $priceProductOfferIds = array_map(
            'intval',
            $this->getFactory()->getUtilEncodingService()->decodeJson($request->get(static::PARAM_PRICE_PRODUCT_OFFER_IDS), true)
        );
        $priceProductOfferCollectionTransfer = $this->createPriceProductOfferCollectionTransferByPriceProductOfferIds($priceProductOfferIds);
        $response = $this->validatePriceProductOfferIds($priceProductOfferCollectionTransfer);
        if ($response) {
            return $response;
        }

        $this->getFactory()->getPriceProductOfferFacade()->deleteProductOfferPrices($priceProductOfferCollectionTransfer);

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
     * @return \Symfony\Component\HttpFoundation\JsonResponse|null
     */
    protected function validatePriceProductOfferIds(PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer): ?JsonResponse
    {
        $constraintViolationList = $this->getFactory()
            ->getValidationAdapter()
            ->createValidator()
            ->validate(
                $priceProductOfferCollectionTransfer,
                $this->getFactory()->createValidProductOfferPriceIdsOwnByMerchantConstraint()
            );

        if ($constraintViolationList->count()) {
            $responseData['notifications'][] = [
                'type' => static::NOTIFICATION_TYPE_ERROR,
                'message' => static::ERROR_MESSAGE,
            ];

            return new JsonResponse($responseData);
        }

        return null;
    }

    /**
     * @param int[] $priceProductOfferIds
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer
     */
    protected function createPriceProductOfferCollectionTransferByPriceProductOfferIds(array $priceProductOfferIds): PriceProductOfferCollectionTransfer
    {
        $priceProductOfferCollectionTransfer = new PriceProductOfferCollectionTransfer();
        $currentMerchantUser = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser();

        foreach ($priceProductOfferIds as $idPriceProductOffer) {
            $priceProductOfferTransfer = (new PriceProductOfferTransfer())->setIdPriceProductOffer($idPriceProductOffer)
                ->setProductOffer(
                    (new ProductOfferTransfer())->setMerchantReference($currentMerchantUser->getMerchantOrFail()->getMerchantReference())
                );
            $priceProductOfferCollectionTransfer->addPriceProductOffer($priceProductOfferTransfer);
        }

        return $priceProductOfferCollectionTransfer;
    }
}
