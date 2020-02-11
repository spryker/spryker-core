<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy;

use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdStrategyPluginInterface;

abstract class AbstractSalesOrderThresholdStrategyPlugin extends AbstractPlugin implements SalesOrderThresholdStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api

     * @return \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer
     */
    public function toTransfer(): SalesOrderThresholdTypeTransfer
    {
        return (new SalesOrderThresholdTypeTransfer())
            ->setKey($this->getKey())
            ->setThresholdGroup($this->getGroup());
    }
}
