<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CollectedDiscountTransfer;

interface CollectedDiscountGroupingStrategyPluginInterface
{
    /**
     * Specification:
     * - Returns true if strategy can be used for the collected discount transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return bool
     */
    public function isApplicable(CollectedDiscountTransfer $collectedDiscountTransfer): bool;

    /**
     * Specification:
     * - Returns group name for the strategy collected discount group.
     *
     * @api
     *
     * @return string
     */
    public function getGroupName(): string;
}
