<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextGui\Dependency\Facade;

class StoreContextGuiToStoreContextFacadeBridge implements StoreContextGuiToStoreContextFacadeInterface
{
    /**
     * @var \Spryker\Zed\StoreContext\Business\StoreContextFacadeInterface
     */
    protected $storeContextFacade;

    /**
     * @param \Spryker\Zed\StoreContext\Business\StoreContextFacadeInterface $storeContextFacade
     */
    public function __construct($storeContextFacade)
    {
        $this->storeContextFacade = $storeContextFacade;
    }

    /**
     * @return array<string, string>
     */
    public function getAvilableTimeZones(): array
    {
        return $this->storeContextFacade->getAvilableTimeZones();
    }

    /**
     * @return array<string>
     */
    public function getAvilableApplications(): array
    {
        return $this->storeContextFacade->getAvilableApplications();
    }
}
