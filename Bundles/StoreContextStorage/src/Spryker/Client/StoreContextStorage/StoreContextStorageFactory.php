<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StoreContextStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\StoreContextStorage\Expander\StoreContextExpander;
use Spryker\Client\StoreContextStorage\Expander\StoreContextExpanderInterface;

/**
 * @method \Spryker\Client\StoreContextStorage\StoreContextStorageConfig getConfig()
 */
class StoreContextStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\StoreContextStorage\Expander\StoreContextExpanderInterface
     */
    public function createStoreContextExpander(): StoreContextExpanderInterface
    {
        return new StoreContextExpander(
            $this->getConfig(),
        );
    }
}
