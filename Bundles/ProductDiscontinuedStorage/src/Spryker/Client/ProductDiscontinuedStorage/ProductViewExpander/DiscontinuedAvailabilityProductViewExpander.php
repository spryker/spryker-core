<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\ProductViewExpander;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface;

class DiscontinuedAvailabilityProductViewExpander implements DiscontinuedAvailabilityProductViewExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface
     */
    protected $productDiscontinuedStorageReader;

    /**
     * @param \Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface $productDiscontinuedStorageReader
     */
    public function __construct(ProductDiscontinuedStorageReaderInterface $productDiscontinuedStorageReader)
    {
        $this->productDiscontinuedStorageReader = $productDiscontinuedStorageReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductVew(ProductViewTransfer $productViewTransfer, string $localeName): ProductViewTransfer
    {
        $productDiscontinuedTransfer = $this->productDiscontinuedStorageReader
            ->findProductDiscontinuedStorage($productViewTransfer->getSku(), $localeName);

        return $productViewTransfer->setAvailable(
            $productViewTransfer->getAvailable() && !$productDiscontinuedTransfer
        );
    }
}
