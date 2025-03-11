<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Sales\Helper;

use Codeception\Module;

trait SalesDataHelperTrait
{
    /**
     * @return \SprykerTest\Shared\Sales\Helper\SalesDataHelper
     */
    protected function getSalesDataHelper(): SalesDataHelper
    {
        /** @var \SprykerTest\Shared\Sales\Helper\SalesDataHelper $salesDataHelper */
        $salesDataHelper = $this->getModule('\\' . SalesDataHelper::class);

        return $salesDataHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule(string $name): Module;
}
