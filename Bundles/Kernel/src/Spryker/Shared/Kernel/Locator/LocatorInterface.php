<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Locator;

interface LocatorInterface
{
    /**
     * @param string $bundle
     *
     * @return object
     */
    public function locate($bundle);
}
