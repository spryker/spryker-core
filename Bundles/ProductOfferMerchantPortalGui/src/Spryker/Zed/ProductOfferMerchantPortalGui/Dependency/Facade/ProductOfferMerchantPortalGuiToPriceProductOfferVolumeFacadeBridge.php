<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade;

class ProductOfferMerchantPortalGuiToPriceProductOfferVolumeFacadeBridge implements ProductOfferMerchantPortalGuiToPriceProductOfferVolumeFacadeInterface
{
    /**
     * @var \Spryker\Zed\PriceProductOfferVolume\Business\PriceProductOfferVolumeFacadeInterface
     */
    protected $priceProductOfferVolumeFacade;

    /**
     * @param \Spryker\Zed\PriceProductOfferVolume\Business\PriceProductOfferVolumeFacadeInterface $priceProductOfferVolumeFacade
     */
    public function __construct($priceProductOfferVolumeFacade)
    {
        $this->priceProductOfferVolumeFacade = $priceProductOfferVolumeFacade;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductOfferTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function extractVolumePrices(array $priceProductOfferTransfers): array
    {
        return $this->priceProductOfferVolumeFacade->extractVolumePrices($priceProductOfferTransfers);
    }
}
