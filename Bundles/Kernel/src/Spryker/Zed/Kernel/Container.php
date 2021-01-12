<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel;

use Spryker\Shared\Kernel\Container\AbstractApplicationContainer;

class Container extends AbstractApplicationContainer
{
    /**
     * @return \Generated\Zed\Ide\AutoCompletion|\Spryker\Shared\Kernel\LocatorLocatorInterface
     */
    public function getLocator()
    {
        return Locator::getInstance();
    }
}
