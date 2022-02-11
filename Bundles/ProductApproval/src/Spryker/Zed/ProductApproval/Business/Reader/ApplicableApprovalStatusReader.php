<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Business\Reader;

use Spryker\Zed\ProductApproval\ProductApprovalConfig;

class ApplicableApprovalStatusReader implements ApplicableApprovalStatusReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductApproval\ProductApprovalConfig
     */
    protected $productApprovalConfig;

    /**
     * @param \Spryker\Zed\ProductApproval\ProductApprovalConfig $productApprovalConfig
     */
    public function __construct(ProductApprovalConfig $productApprovalConfig)
    {
        $this->productApprovalConfig = $productApprovalConfig;
    }

    /**
     * @param string $currentStatus
     *
     * @return array<string>
     */
    public function getApplicableApprovalStatuses(string $currentStatus): array
    {
        return $this->productApprovalConfig->getStatusTree()[$currentStatus] ?? [];
    }
}
