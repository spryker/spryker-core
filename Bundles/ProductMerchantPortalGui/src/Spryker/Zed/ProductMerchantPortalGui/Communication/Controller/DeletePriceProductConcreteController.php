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
class DeletePriceProductConcreteController extends AbstractDeletePriceProductController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idProductConcrete = (int)$request->get(PriceProductTableViewTransfer::ID_PRODUCT_CONCRETE);

        if (!$idProductConcrete) {
            return $this->createErrorResponse();
        }

        $idMerchant = $this->getIdMerchantFromCurrentUser();
        $productConcreteTransfer = $this->getFactory()->getMerchantProductFacade()->findProductConcrete(
            (new MerchantProductCriteriaTransfer())
                ->setIdMerchant($idMerchant)
                ->addIdProductConcrete($idProductConcrete)
        );

        if (!$productConcreteTransfer) {
            return $this->createErrorResponse();
        }

        $this->deletePrices(
            $productConcreteTransfer->getPrices(),
            $this->getDefaultPriceProductIds($request)
        );

        return $this->createSuccessResponse();
    }
}
