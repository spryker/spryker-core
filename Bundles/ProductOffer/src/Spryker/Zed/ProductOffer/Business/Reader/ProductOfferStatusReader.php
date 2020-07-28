<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\Reader;

use Spryker\Zed\ProductOffer\ProductOfferConfig;

class ProductOfferStatusReader implements ProductOfferStatusReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOffer\ProductOfferConfig
     */
    protected $productOfferConfig;

    /**
     * @param \Spryker\Zed\ProductOffer\ProductOfferConfig $productOfferConfig
     */
    public function __construct(ProductOfferConfig $productOfferConfig)
    {
        $this->productOfferConfig = $productOfferConfig;
    }

    /**
     * @param string $currentStatus
     *
     * @return string[]
     */
    public function getApplicableApprovalStatuses(string $currentStatus): array
    {
        $statusTree = $this->productOfferConfig->getStatusTree();

        return $statusTree[$currentStatus] ?? [];
    }
}
