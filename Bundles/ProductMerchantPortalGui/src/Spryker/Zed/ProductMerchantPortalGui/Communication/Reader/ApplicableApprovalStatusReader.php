<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Reader;

use Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig;

class ApplicableApprovalStatusReader implements ApplicableApprovalStatusReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig
     */
    protected $productMerchantPortalGuiConfig;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig $productMerchantPortalGuiConfig
     */
    public function __construct(ProductMerchantPortalGuiConfig $productMerchantPortalGuiConfig)
    {
        $this->productMerchantPortalGuiConfig = $productMerchantPortalGuiConfig;
    }

    /**
     * @param string $currentStatus
     *
     * @return array<string>
     */
    public function getApplicableUpdateApprovalStatuses(string $currentStatus): array
    {
        return $this->productMerchantPortalGuiConfig->getProductApprovalUpdateStatusTree()[$currentStatus] ?? [];
    }
}
