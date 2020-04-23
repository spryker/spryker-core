<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StoreStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\StoreStorage\StoreStorageFactory getFactory()
 */
class StoreStorageClient extends AbstractClient implements StoreStorageClientInterface
{
    /**
     * @inheritDoc
     *
     * @api
     *
     * @return string[]
     */
    public function getAllStores(): array
    {
        return $this->getFactory()->getStore()->getAllowedStores();
    }
}
