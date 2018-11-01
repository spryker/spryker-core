<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageStorage\Resolver;

use Spryker\Client\ProductImageStorage\Storage\ProductAbstractImageStorageReaderInterface;
use Spryker\Client\ProductImageStorage\Storage\ProductConcreteImageStorageReaderInterface;

class ProductConcreteImageInheritanceResolver implements ProductConcreteImageInheritanceResolverInterface
{
    /**
     * @var \Spryker\Client\ProductImageStorage\Storage\ProductConcreteImageStorageReaderInterface
     */
    protected $productConcreteImageStorageReader;

    /**
     * @var \Spryker\Client\ProductImageStorage\Storage\ProductAbstractImageStorageReaderInterface
     */
    protected $productAbstractImageStorageReader;

    /**
     * @param \Spryker\Client\ProductImageStorage\Storage\ProductConcreteImageStorageReaderInterface $productConcreteImageStorageReader
     * @param \Spryker\Client\ProductImageStorage\Storage\ProductAbstractImageStorageReaderInterface $productAbstractImageStorageReader
     */
    public function __construct(ProductConcreteImageStorageReaderInterface $productConcreteImageStorageReader, ProductAbstractImageStorageReaderInterface $productAbstractImageStorageReader)
    {
        $this->productConcreteImageStorageReader = $productConcreteImageStorageReader;
        $this->productAbstractImageStorageReader = $productAbstractImageStorageReader;
    }

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductImageSetStorageTransfer[]|null
     */
    public function resolveProductImageSetStorageTransfers(
        int $idProductConcrete,
        int $idProductAbstract,
        string $locale
    ): ?array {
        $productImageStorageTransfer = $this->productConcreteImageStorageReader
            ->findProductImageConcreteStorageTransfer($idProductConcrete, $locale);

        if ($productImageStorageTransfer === null) {
            $productImageStorageTransfer = $this->productAbstractImageStorageReader
                ->findProductImageAbstractStorageTransfer($idProductAbstract, $locale);
        }

        if ($productImageStorageTransfer === null) {
            return null;
        }

        if ($productImageStorageTransfer->getImageSets()->count() === 0) {
            return null;
        }

        return $productImageStorageTransfer->getImageSets()->getArrayCopy();
    }
}
