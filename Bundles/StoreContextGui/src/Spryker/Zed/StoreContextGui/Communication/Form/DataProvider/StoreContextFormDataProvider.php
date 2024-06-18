<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextGui\Communication\Form\DataProvider;

use Spryker\Zed\StoreContextGui\Dependency\Facade\StoreContextGuiToStoreContextFacadeInterface;

class StoreContextFormDataProvider
{
    /**
     * @var \Spryker\Zed\StoreContextGui\Dependency\Facade\StoreContextGuiToStoreContextFacadeInterface
     */
    protected StoreContextGuiToStoreContextFacadeInterface $storeContextFacade;

    /**
     * @param \Spryker\Zed\StoreContextGui\Dependency\Facade\StoreContextGuiToStoreContextFacadeInterface $storeContextFacade
     */
    public function __construct(StoreContextGuiToStoreContextFacadeInterface $storeContextFacade)
    {
        $this->storeContextFacade = $storeContextFacade;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            'timezones' => $this->getTimezones(),
            'applications' => $this->storeContextFacade->getAvilableApplications(),
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function getTimezones(): array
    {
        return array_combine($this->storeContextFacade->getAvilableTimeZones(), $this->storeContextFacade->getAvilableTimeZones());
    }
}
