<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade;

use ArrayObject;
use Generated\Shared\Transfer\ValidationResponseTransfer;

class ProductMerchantPortalGuiToPriceProductVolumeFacadeBridge implements ProductMerchantPortalGuiToPriceProductVolumeFacadeInterface
{
    /**
     * @var \Spryker\Zed\PriceProductVolume\Business\PriceProductVolumeFacadeInterface
     */
    protected $priceProductVolumeFacade;

    /**
     * @param \Spryker\Zed\PriceProductVolume\Business\PriceProductVolumeFacadeInterface $priceProductVolumeFacade
     */
    public function __construct($priceProductVolumeFacade)
    {
        $this->priceProductVolumeFacade = $priceProductVolumeFacade;
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validateVolumePrices(ArrayObject $priceProductTransfers): ValidationResponseTransfer
    {
        return $this->priceProductVolumeFacade->validateVolumePrices($priceProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractPriceProductVolumeTransfersFromArray(array $priceProductTransfers): array
    {
        return $this->priceProductVolumeFacade->extractPriceProductVolumeTransfersFromArray($priceProductTransfers);
    }
}
