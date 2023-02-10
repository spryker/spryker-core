<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Filter\PriceProductFilterInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductOfferFacadeInterface;

class PriceProductOfferDataProvider implements PriceProductOfferDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductOfferFacadeInterface
     */
    protected ProductOfferMerchantPortalGuiToProductOfferFacadeInterface $productOfferFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Filter\PriceProductFilterInterface
     */
    protected PriceProductFilterInterface $priceProductFilter;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductOfferFacadeInterface $productOfferFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Filter\PriceProductFilterInterface $priceProductFilter
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductOfferMerchantPortalGuiToProductOfferFacadeInterface $productOfferFacade,
        PriceProductFilterInterface $priceProductFilter
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
        $this->productOfferFacade = $productOfferFacade;
        $this->priceProductFilter = $priceProductFilter;
    }

    /**
     * @param int $idProductOffer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getPriceProductTransfers(int $idProductOffer): ArrayObject
    {
        $currentMerchantReference = $this->merchantUserFacade
            ->getCurrentMerchantUser()
            ->getMerchantOrFail()
            ->getMerchantReferenceOrFail();

        $productOfferCriteriaTransfer = (new ProductOfferCriteriaTransfer())
            ->setIdProductOffer($idProductOffer)
            ->addMerchantReference($currentMerchantReference);

        $productOfferTransfer = $this->productOfferFacade->findOne($productOfferCriteriaTransfer);

        if (!$productOfferTransfer) {
            return new ArrayObject();
        }

        return new ArrayObject(
            $this->priceProductFilter->filterPriceProductTransfers(
                $productOfferTransfer->getPrices()->getArrayCopy(),
                (new PriceProductOfferCriteriaTransfer())->addVolumeQuantity(1),
            ),
        );
    }
}
