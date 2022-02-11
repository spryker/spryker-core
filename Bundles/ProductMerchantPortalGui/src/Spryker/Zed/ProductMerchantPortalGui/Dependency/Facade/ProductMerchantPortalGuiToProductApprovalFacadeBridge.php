<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade;

class ProductMerchantPortalGuiToProductApprovalFacadeBridge implements ProductMerchantPortalGuiToProductApprovalFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductApproval\Business\ProductApprovalFacadeInterface
     */
    protected $productApprovalFacade;

    /**
     * @param \Spryker\Zed\ProductApproval\Business\ProductApprovalFacadeInterface $productApprovalFacade
     */
    public function __construct($productApprovalFacade)
    {
        $this->productApprovalFacade = $productApprovalFacade;
    }

    /**
     * @param string $currentStatus
     *
     * @return array<string>
     */
    public function getApplicableApprovalStatuses(string $currentStatus): array
    {
        return $this->productApprovalFacade->getApplicableApprovalStatuses($currentStatus);
    }
}
