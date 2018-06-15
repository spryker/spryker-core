<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Store;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Store\StoreFactory getFactory()
 */
class StoreClient extends AbstractClient implements StoreClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore()
    {
        return $this->getFactory()
            ->createStoreReader()
            ->getCurrentStore();
    }
}
