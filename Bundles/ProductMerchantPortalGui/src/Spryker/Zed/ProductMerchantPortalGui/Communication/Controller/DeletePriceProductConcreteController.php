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
        $volumeQuantity = (int)$request->get(PriceProductTableViewTransfer::VOLUME_QUANTITY);

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

        $validationResponseTransfer = $this->deletePrices(
            $productConcreteTransfer->getPrices(),
            $this->getDefaultPriceProductIds($request),
            $volumeQuantity
        );

        if (!$validationResponseTransfer->getIsSuccess()) {
            /** @var \Generated\Shared\Transfer\ValidationErrorTransfer $validationErrorTransfer */
            $validationErrorTransfer = $validationResponseTransfer->getValidationErrors()->offsetGet(0);

            return $this->createErrorResponse($validationErrorTransfer->getMessageOrFail());
        }

        return $this->createSuccessResponse();
    }
}
