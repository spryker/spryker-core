<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Communication\Plugin\Strategy;

use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueStrategyPluginInterface;

abstract class AbstractMinimumOrderValueStrategyPlugin extends AbstractPlugin implements MinimumOrderValueStrategyPluginInterface
{
    /**
     * {inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer
     *
     * @return bool
     */
    public function isApplicable(MinimumOrderValueThresholdTransfer $minimumOrderValueThresholdTransfer): bool
    {
        return $minimumOrderValueThresholdTransfer->getValue() < $minimumOrderValueThresholdTransfer->getThreshold();
    }

    /**
     * {inheritdoc}
     *
     * @api

     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer
     */
    public function toTransfer(): MinimumOrderValueTypeTransfer
    {
        return (new MinimumOrderValueTypeTransfer())
            ->setKey($this->getKey())
            ->setThresholdGroup($this->getGroup());
    }
}
