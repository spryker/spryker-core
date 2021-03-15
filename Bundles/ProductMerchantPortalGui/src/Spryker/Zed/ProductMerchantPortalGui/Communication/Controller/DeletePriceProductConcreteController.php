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
class DeletePriceProductConcreteController extends DeletePriceProductController
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
            )
        );
        $idProductConcrete = (int)$request->get(PriceProductTableViewTransfer::ID_PRODUCT_CONCRETE);

        if (!$idProductConcrete) {
            return $this->getErrorResponse();
        }

        $idMerchant = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser()->getIdMerchantOrFail();
        $productConcreteTransfer = $this->getFactory()->getMerchantProductFacade()->findProductConcrete(
            (new MerchantProductCriteriaTransfer())->addIdMerchant($idMerchant)->addIdProductConcrete($idProductConcrete)
        );

        if (!$productConcreteTransfer) {
            return $this->getErrorResponse();
        }

        $priceProductTransfersToRemove = $this->getPriceProductTransfersToRemove(
            $productConcreteTransfer->getPrices(),
            $priceProductDefaultIds
        );

        foreach ($priceProductTransfersToRemove as $priceProductTransfer) {
            $this->getFactory()->getPriceProductFacade()->removePriceProductDefaultForPriceProduct($priceProductTransfer);
        }

        return $this->getSuccessResponse();
    }
}
