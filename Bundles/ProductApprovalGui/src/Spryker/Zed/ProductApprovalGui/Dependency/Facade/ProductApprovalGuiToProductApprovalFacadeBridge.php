<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApprovalGui\Dependency\Facade;

class ProductApprovalGuiToProductApprovalFacadeBridge implements ProductApprovalGuiToProductApprovalFacadeInterface
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
