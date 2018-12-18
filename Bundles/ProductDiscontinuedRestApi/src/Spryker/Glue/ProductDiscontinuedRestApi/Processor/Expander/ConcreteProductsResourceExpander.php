<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductDiscontinuedRestApi\Processor\Expander;

use Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer;
use Spryker\Glue\ProductDiscontinuedRestApi\Dependency\Client\ProductDiscontinuedRestApiToProductDiscontinuedStorageClientInterface;

class ConcreteProductsResourceExpander implements ConcreteProductsResourceExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductDiscontinuedRestApi\Dependency\Client\ProductDiscontinuedRestApiToProductDiscontinuedStorageClientInterface
     */
    protected $productDiscontinuedStorageClient;

    /**
     * @param \Spryker\Glue\ProductDiscontinuedRestApi\Dependency\Client\ProductDiscontinuedRestApiToProductDiscontinuedStorageClientInterface $productDiscontinuedStorageClient
     */
    public function __construct(ProductDiscontinuedRestApiToProductDiscontinuedStorageClientInterface $productDiscontinuedStorageClient)
    {
        $this->productDiscontinuedStorageClient = $productDiscontinuedStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer
     */
    public function expand(
        ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer,
        string $localeName
    ): ConcreteProductsRestAttributesTransfer {
        $productDiscontinuedStorageTransfer = $this->productDiscontinuedStorageClient
            ->findProductDiscontinuedStorage($concreteProductsRestAttributesTransfer->getSku(), $localeName);
        if (!$productDiscontinuedStorageTransfer) {
            $concreteProductsRestAttributesTransfer->setIsDiscontinued(false);
            $concreteProductsRestAttributesTransfer->setDiscontinuedNote(null);

            return $concreteProductsRestAttributesTransfer;
        }

        $concreteProductsRestAttributesTransfer->setIsDiscontinued(true);
        $concreteProductsRestAttributesTransfer->setDiscontinuedNote($productDiscontinuedStorageTransfer->getNote());

        return $concreteProductsRestAttributesTransfer;
    }
}
