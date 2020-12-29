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
        $productAbstractTransfer = $this->getFactory()->getMerchantProductFacade()->findProductAbstract(
            (new MerchantProductCriteriaTransfer())->addIdMerchant($idMerchant)->setIdProductAbstract($idProductAbstract)
        );

        if (!$productAbstractTransfer) {
            return $this->getErrorResponse();
        }

        $priceProductTransfersToRemove = $this->getProductTransfersToRemove($productAbstractTransfer, $priceProductDefaultIds);

        foreach ($priceProductTransfersToRemove as $priceProductTransfer) {
            $this->getFactory()->getPriceProductFacade()->removePriceProductDefaultForPriceProduct($priceProductTransfer);
        }

        $responseData = [
            'postActions' => [
                [
                    'type' => 'refresh_table',
                ],
            ],
            'notifications' => [
                [
                    'type' => 'success',
                    'message' => 'Success! The Price is deleted.',
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
        $responseData['notifications'][] = [
            'type' => 'error',
            'message' => 'Something went wrong, please try again.',
        ];

        return new JsonResponse($responseData);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param int[] $priceProductDefaultIds
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function getProductTransfersToRemove(
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
