<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\Dependency\Client;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;

class QuickOrderToPriceProductClientBridge implements QuickOrderToPriceProductClientInterface
{
    /**
     * @var \Spryker\Client\PriceProduct\PriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @param \Spryker\Client\PriceProduct\PriceProductClientInterface $priceProductClient
     */
    public function __construct($priceProductClient)
    {
        $this->priceProductClient = $priceProductClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function calculateProductSumPrice(
        CurrentProductPriceTransfer $currentProductPriceTransfer,
        PriceProductFilterTransfer $priceProductFilterTransfer,
        array $priceProductTransfers
    ): CurrentProductPriceTransfer {
        return $this->priceProductClient->calculateProductSumPrice($currentProductPriceTransfer, $priceProductFilterTransfer, $priceProductTransfers);
    }
}
