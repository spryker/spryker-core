<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage;

use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageFactory getFactory()
 */
class ProductDiscontinuedStorageClient extends AbstractClient implements ProductDiscontinuedStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedTransfer|null
     */
    public function findProductDiscontinuedStorage(string $concreteSku): ?ProductDiscontinuedTransfer
    {
        return $this->getFactory()
            ->createProductDiscontinuedStorageReader()
            ->findProductDiscontinuedStorage($concreteSku);
    }
}
