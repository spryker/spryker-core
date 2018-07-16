<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOptionStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductOptionStorage\ProductOptionStorageFactory getFactory()
 */
class ProductOptionStorageClient extends AbstractClient implements ProductOptionStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idAbstractProduct
     * @param int $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer
     */
    public function getProductOptions($idAbstractProduct, $localeName)
    {
        return $this->getFactory()
            ->createProductOptionStorageReader()
            ->getProductOptions($idAbstractProduct, $localeName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idAbstractProduct
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer
     */
    public function getProductOptionsForCurrentStore($idAbstractProduct)
    {
        return $this->getFactory()
            ->createProductOptionStorageReader()
            ->getProductOptionsForCurrentStore($idAbstractProduct);
    }
}
