<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductGroupStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductGroupStorage\ProductGroupStorageFactory getFactory()
 */
class ProductGroupStorageClient extends AbstractClient implements ProductGroupStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractGroupStorageTransfer
     */
    public function findProductGroupItemsByIdProductAbstract($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductGroupStorage()
            ->findProductGroupItemsByIdProductAbstract($idProductAbstract);
    }
}
