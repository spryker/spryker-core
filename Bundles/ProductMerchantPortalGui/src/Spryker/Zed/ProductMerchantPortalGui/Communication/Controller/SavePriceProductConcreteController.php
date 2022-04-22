<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class SavePriceProductConcreteController extends AbstractSavePriceProductController
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
            $priceProductTransfer->setIdProduct(
                $this->castId($request->get(PriceProductTableViewTransfer::ID_PRODUCT_CONCRETE)),
            );
        }

        return $priceProductTransfers;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function findPriceProductTransfers(Request $request): array
    {
        $idProductConcrete = $this->castId($request->get(PriceProductTableViewTransfer::ID_PRODUCT_CONCRETE));
        $idProductAbstract = $this->castId(
            $this->getFactory()->getProductFacade()->findProductAbstractIdByConcreteId($idProductConcrete),
        );
        $priceProductCriteriaTransfer = $this->getFactory()
            ->createPriceProductMapper()
            ->mapRequestDataToPriceProductCriteriaTransfer(
                $request->query->all(),
                (new PriceProductCriteriaTransfer())->setOnlyConcretePrices(true),
            );

        return array_values($this->getFactory()
            ->getPriceProductFacade()
            ->findProductConcretePricesWithoutPriceExtraction(
                $idProductConcrete,
                $idProductAbstract,
                $priceProductCriteriaTransfer,
            ));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isProductOwnedByCurrentMerchant(Request $request): bool
    {
        $idProductConcrete = $this->castId($request->get(PriceProductTableViewTransfer::ID_PRODUCT_CONCRETE));

        $productConcreteTransfer = (new ProductConcreteTransfer())->setIdProductConcrete($idProductConcrete);
        $merchantTransfer = $this->getFactory()
            ->getMerchantUserFacade()
            ->getCurrentMerchantUser()
            ->getMerchantOrFail();

        return $this->getFactory()
            ->getMerchantProductFacade()
            ->isProductConcreteOwnedByMerchant($productConcreteTransfer, $merchantTransfer);
    }
}
