<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\StoreContextGui\Communication\Form\DataProvider\StoreTimezoneFormDataProvider;
use Spryker\Zed\StoreContextGui\Communication\Form\StoreTimezoneForm;

/**
 * @method \Spryker\Zed\StoreContextGui\StoreContextGuiConfig getConfig()
 */
class StoreContextGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\StoreContextGui\Communication\Form\StoreTimezoneForm
     */
    public function createStoreTimezoneForm(): StoreTimezoneForm
    {
        return new StoreTimezoneForm();
    }

    /**
     * @return \Spryker\Zed\StoreContextGui\Communication\Form\DataProvider\StoreTimezoneFormDataProvider
     */
    public function createStoreTimezoneFormDataProvider(): StoreTimezoneFormDataProvider
    {
        return new StoreTimezoneFormDataProvider();
    }
}
