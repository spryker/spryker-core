<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApprovalGui\Communication\Reader;

use Spryker\Zed\ProductApprovalGui\Dependency\Facade\ProductApprovalGuiToProductApprovalFacadeInterface;
use Spryker\Zed\ProductApprovalGui\ProductApprovalGuiConfig;

class ProductApprovalStatusReader implements ProductApprovalStatusReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductApprovalGui\ProductApprovalGuiConfig
     */
    protected $productApprovalGuiConfig;

    /**
     * @var \Spryker\Zed\ProductApprovalGui\Dependency\Facade\ProductApprovalGuiToProductApprovalFacadeInterface
     */
    protected $productApprovalFacade;

    /**
     * @param \Spryker\Zed\ProductApprovalGui\ProductApprovalGuiConfig $productApprovalGuiConfig
     * @param \Spryker\Zed\ProductApprovalGui\Dependency\Facade\ProductApprovalGuiToProductApprovalFacadeInterface $productApprovalFacade
     */
    public function __construct(
        ProductApprovalGuiConfig $productApprovalGuiConfig,
        ProductApprovalGuiToProductApprovalFacadeInterface $productApprovalFacade
    ) {
        $this->productApprovalGuiConfig = $productApprovalGuiConfig;
        $this->productApprovalFacade = $productApprovalFacade;
    }

    /**
     * @param string $currentStatus
     *
     * @return array<string>
     */
    public function getApplicableTableActionApprovalStatuses(string $currentStatus): array
    {
        if ($this->productApprovalGuiConfig->isApprovalStatusTreeCustomizationEnabled()) {
            return $this->productApprovalGuiConfig->getProductApprovalTableActionStatusTree()[$currentStatus] ?? [];
        }

        return $this->productApprovalFacade->getApplicableApprovalStatuses($currentStatus);
    }
}
