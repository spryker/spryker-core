<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOption;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductOption\ProductOptionFactory getFactory()
 */
class ProductOptionClient extends AbstractClient implements ProductOptionClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idAbstractProduct
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductOptionGroupCollectionTransfer
     */
    public function getProductOptions($idAbstractProduct, $localeName)
    {
        return $this->getFactory()
            ->createProductOptionStorage($localeName)
            ->get($idAbstractProduct);
    }
}
