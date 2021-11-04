<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Reader;

use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Filter\PriceProductFilterInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface;

class PriceProductReader implements PriceProductReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface
     */
    protected $priceProductOfferFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Filter\PriceProductFilterInterface
     */
    protected $priceProductFilter;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface $priceProductOfferFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Filter\PriceProductFilterInterface $priceProductFilter
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface $priceProductOfferFacade,
        PriceProductFilterInterface $priceProductFilter
    ) {
        $this->priceProductOfferFacade = $priceProductOfferFacade;
        $this->priceProductFilter = $priceProductFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getPriceProductTransfers(
        PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
    ): array {
        $priceProductTransfers = $this->priceProductOfferFacade
            ->getProductOfferPrices(
                (new PriceProductOfferCriteriaTransfer())->setIdProductOffer(
                    $priceProductOfferCriteriaTransfer->getIdProductOfferOrFail(),
                ),
            )
            ->getArrayCopy();

        $priceProductTransfers = $this->priceProductFilter
            ->filterPriceProductTransfers($priceProductTransfers, $priceProductOfferCriteriaTransfer);

        return $priceProductTransfers;
    }
}
