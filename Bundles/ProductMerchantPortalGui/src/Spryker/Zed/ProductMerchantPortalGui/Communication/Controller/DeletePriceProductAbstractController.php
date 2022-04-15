<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class DeletePriceProductAbstractController extends AbstractDeletePriceProductController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idProductAbstract = (int)$request->get(PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT);
        $volumeQuantity = (int)$request->get(PriceProductTableViewTransfer::VOLUME_QUANTITY);

        if (!$idProductAbstract) {
            return $this->createErrorResponse();
        }

        $idMerchant = $this->getIdMerchantFromCurrentUser();
        $merchantProductTransfer = $this->getFactory()->getMerchantProductFacade()->findMerchantProduct(
            (new MerchantProductCriteriaTransfer())
                ->addIdMerchant($idMerchant)
                ->setIdProductAbstract($idProductAbstract),
        );

        if (!$merchantProductTransfer || !$merchantProductTransfer->getProductAbstract()) {
            return $this->createErrorResponse();
        }

        $priceProductTransfersToRemove = $this->getPriceProductTransfers($request);

        $validationResponseTransfer = $this->getFactory()->createPriceDeleter()->deletePrices(
            $priceProductTransfersToRemove,
            $volumeQuantity,
        );

        if (!$validationResponseTransfer->getIsSuccess()) {
            /** @var \Generated\Shared\Transfer\ValidationErrorTransfer $validationErrorTransfer */
            $validationErrorTransfer = $validationResponseTransfer->getValidationErrors()->offsetGet(0);

            return $this->createErrorResponse($validationErrorTransfer->getMessageOrFail());
        }

        return $this->createSuccessResponse();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function getPriceProductTransfers(Request $request): array
    {
        $priceProductCriteriaTransfer = $this->getFactory()
            ->createPriceProductMapper()
            ->mapRequestDataToPriceProductCriteriaTransfer($request->query->all(), new PriceProductCriteriaTransfer());

        return $this->getFactory()
            ->getPriceProductFacade()
            ->findProductAbstractPricesWithoutPriceExtraction(
                $this->castId($request->get(PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT)),
                $priceProductCriteriaTransfer,
            );
    }
}
