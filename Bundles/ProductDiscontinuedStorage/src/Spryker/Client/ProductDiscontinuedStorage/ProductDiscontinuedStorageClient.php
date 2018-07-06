<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage;

use Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
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
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer|null
     */
    public function findProductDiscontinuedStorage(string $concreteSku, string $locale): ?ProductDiscontinuedStorageTransfer
    {
        return $this->getFactory()
            ->createProductDiscontinuedStorageReader()
            ->findProductDiscontinuedStorage($concreteSku, $locale);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $concreteSku
     * @param string $locale
     *
     * @return bool
     */
    public function isProductDiscontinuedStorage(string $concreteSku, string $locale): bool
    {
        return (bool)$this->getFactory()
            ->createProductDiscontinuedStorageReader()
            ->findProductDiscontinuedStorage($concreteSku, $locale);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandDiscontinuedProductSuperAttributes(ProductViewTransfer $productViewTransfer, string $localeName): ProductViewTransfer
    {
        return $this->getFactory()
            ->createDiscontinuedSuperAttributesProductViewExpander()
            ->expandDiscontinuedProductSuperAttributes($productViewTransfer, $localeName);
    }
}
