<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class DeletePriceProductAbstractController extends DeletePriceProductController
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
                $request->get(PriceProductTableViewTransfer::PRICE_PRODUCT_DEFAULT_IDS),
                true
            ) ?: []
        );
        $idProductAbstract = (int)$request->get(PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT);

        if (!$idProductAbstract) {
            return $this->createErrorResponse();
        }

        $idMerchant = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser()->getIdMerchantOrFail();
        $merchantProductTransfer = $this->getFactory()->getMerchantProductFacade()->findMerchantProduct(
            (new MerchantProductCriteriaTransfer())->addIdMerchant($idMerchant)->setIdProductAbstract($idProductAbstract)
        );

        if (!$merchantProductTransfer || !$merchantProductTransfer->getProductAbstract()) {
            return $this->createErrorResponse();
        }

        $priceProductTransfersToRemove = $this->filterPriceProductTransfersByPriceProductDefaultIds(
            $merchantProductTransfer->getProductAbstractOrFail()->getPrices(),
            $priceProductDefaultIds
        );

        foreach ($priceProductTransfersToRemove as $priceProductTransfer) {
            $this->getFactory()->getPriceProductFacade()->removePriceProductDefaultForPriceProduct($priceProductTransfer);
        }

        return $this->createSuccessResponse();
    }
}
