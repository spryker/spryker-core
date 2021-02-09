<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductAbstractTableViewTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class DeletePriceProductAbstractController extends AbstractController
{
    protected const RESPONSE_MESSAGE_SUCCESS = 'Success! The Price is deleted.';
    protected const RESPONSE_MESSAGE_ERROR = 'Something went wrong, please try again.';

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
        $priceProductDefaultIds = array_map(
            'intval',
            $this->getFactory()->getUtilEncodingService()->decodeJson(
                $request->get(PriceProductAbstractTableViewTransfer::PRICE_PRODUCT_DEFAULT_IDS),
                true
            )
        );
        $idProductAbstract = (int)$request->get(PriceProductAbstractTableViewTransfer::ID_PRODUCT_ABSTRACT);

        if (!$idProductAbstract) {
            return $this->getErrorResponse();
        }

        $idMerchant = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser()->getIdMerchant();
        $merchantProductTransfer = $this->getFactory()->getMerchantProductFacade()->findMerchantProduct(
            (new MerchantProductCriteriaTransfer())->addIdMerchant($idMerchant)->setIdProductAbstract($idProductAbstract)
        );

        if (!$merchantProductTransfer || !$merchantProductTransfer->getProductAbstract()) {
            return $this->getErrorResponse();
        }

        $priceProductTransfersToRemove = $this->getPriceProductTransfersToRemove(
            $merchantProductTransfer->getProductAbstract(),
            $priceProductDefaultIds
        );

        foreach ($priceProductTransfersToRemove as $priceProductTransfer) {
            $this->getFactory()->getPriceProductFacade()->removePriceProductDefaultForPriceProduct($priceProductTransfer);
        }

        $responseData = [
            static::RESPONSE_KEY_POST_ACTIONS => [
                [
                    static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_REFRESH_TABLE,
                ],
            ],
            static::RESPONSE_KEY_NOTIFICATIONS => [
                [
                    static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_SUCCESS,
                    static::RESPONSE_KEY_MESSAGE => static::RESPONSE_MESSAGE_SUCCESS,
                ],
            ],
        ];

        return new JsonResponse($responseData);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getErrorResponse(): JsonResponse
    {
        $responseData[static::RESPONSE_KEY_NOTIFICATIONS][] = [
            static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_ERROR,
            static::RESPONSE_KEY_MESSAGE => static::RESPONSE_MESSAGE_ERROR,
        ];

        return new JsonResponse($responseData);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param int[] $priceProductDefaultIds
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function getPriceProductTransfersToRemove(
        ProductAbstractTransfer $productAbstractTransfer,
        array $priceProductDefaultIds
    ): array {
        $priceProductTransfersToRemove = [];

        foreach ($productAbstractTransfer->getPrices() as $priceProductTransfer) {
            if (in_array($priceProductTransfer->getPriceDimension()->getIdPriceProductDefault(), $priceProductDefaultIds)) {
                $priceProductTransfersToRemove[] = $priceProductTransfer;
            }
        }

        return $priceProductTransfersToRemove;
    }
}
