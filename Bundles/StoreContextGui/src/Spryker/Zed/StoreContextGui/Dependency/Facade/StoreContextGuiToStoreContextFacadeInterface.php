<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextGui\Dependency\Facade;

interface StoreContextGuiToStoreContextFacadeInterface
{
    /**
     * @return array<string>
     */
    public function getAvilableTimeZones(): array;

    /**
     * @return array<string>
     */
    public function getAvilableApplications(): array;
}
