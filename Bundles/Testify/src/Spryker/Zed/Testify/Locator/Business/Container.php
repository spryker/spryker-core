<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Testify\Locator\Business;

use Spryker\Shared\Testify\Container\TestifyContainerInterface;
use Spryker\Zed\Kernel\Container as KernelContainer;

class Container extends KernelContainer implements TestifyContainerInterface
{
    /**
     * @var \Spryker\Zed\Testify\Locator\Business\BusinessLocator
     */
    private $locator;

    /**
     * @param \Spryker\Zed\Testify\Locator\Business\BusinessLocator $locator
     *
     * @return void
     */
    public function setLocator(BusinessLocator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @return \Spryker\Zed\Testify\Locator\Business\BusinessLocator
     */
    public function getLocator()
    {
        return $this->locator;
    }
}
