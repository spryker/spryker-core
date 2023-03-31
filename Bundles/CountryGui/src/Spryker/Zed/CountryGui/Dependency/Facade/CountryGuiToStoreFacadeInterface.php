<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CountryGui\Dependency\Facade;

interface CountryGuiToStoreFacadeInterface
{
    /**
     * @return bool
     */
    public function isDynamicStoreEnabled(): bool;
}
