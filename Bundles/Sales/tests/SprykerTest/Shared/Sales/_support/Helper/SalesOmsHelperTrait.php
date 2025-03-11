<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Sales\Helper;

use Codeception\Module;

trait SalesOmsHelperTrait
{
    /**
     * @return \SprykerTest\Shared\Sales\Helper\SalesOmsHelper
     */
    protected function getSalesOmsHelper(): SalesOmsHelper
    {
        /** @var \SprykerTest\Shared\Sales\Helper\SalesOmsHelper $salesOmsHelper */
        $salesOmsHelper = $this->getModule('\\' . SalesOmsHelper::class);

        return $salesOmsHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule(string $name): Module;
}
