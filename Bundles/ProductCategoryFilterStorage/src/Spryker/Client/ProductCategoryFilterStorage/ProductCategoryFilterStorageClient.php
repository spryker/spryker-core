<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryFilterStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductCategoryFilterStorage\ProductCategoryFilterStorageFactory getFactory()
 */
class ProductCategoryFilterStorageClient extends AbstractClient implements ProductCategoryFilterStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterStorageTransfer|null
     */
    public function getProductCategoryFilterByIdCategory($idCategory)
    {
        return $this->getFactory()
            ->createProductCategoryFilterStorageReader()
            ->getProductCategoryFilter($idCategory);
    }
}
