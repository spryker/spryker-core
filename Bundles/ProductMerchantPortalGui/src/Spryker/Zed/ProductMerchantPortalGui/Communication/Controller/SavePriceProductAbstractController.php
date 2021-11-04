<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class SavePriceProductAbstractController extends AbstractSavePriceProductController
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function expandPriceProductTransfersWithProductId(ArrayObject $priceProductTransfers, Request $request): ArrayObject
    {
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceProductTransfer->setIdProductAbstract(
                $this->castId($request->get(PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT)),
            );
        }

        return $priceProductTransfers;
    }

    /**
     * @param array<int> $priceProductStoreIds
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function findPriceProductTransfers(array $priceProductStoreIds, Request $request): array
    {
        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
            ->setPriceProductStoreIds($priceProductStoreIds);

        return $this->getFactory()
            ->getPriceProductFacade()
            ->findProductAbstractPricesWithoutPriceExtraction(
                $this->castId($request->get(PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT)),
                $priceProductCriteriaTransfer,
            );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isProductOwnedByCurrentMerchant(Request $request): bool
    {
        $idProductAbstract = $this->castId($request->get(PriceProductTableViewTransfer::ID_PRODUCT_ABSTRACT));
        $merchantTransfer = $this->getFactory()
            ->getMerchantUserFacade()
            ->getCurrentMerchantUser()
            ->getMerchantOrFail();

        $productAbstractTransfer = (new ProductAbstractTransfer())->setIdProductAbstract($idProductAbstract);

        return $this->getFactory()
            ->getMerchantProductFacade()
            ->isProductAbstractOwnedByMerchant($productAbstractTransfer, $merchantTransfer);
    }
}
