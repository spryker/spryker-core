<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchWriter;

use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchEntityManagerInterface;

class ProductConcretePageSearchWriter implements ProductConcretePageSearchWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchEntityManagerInterface $entityManager
     */
    public function __construct(ProductPageSearchEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer
     */
    public function saveProductConcretePageSearch(ProductConcretePageSearchTransfer $productConcretePageSearchTransfer): ProductConcretePageSearchTransfer
    {
        return $this->entityManager->saveProductConcretePageSearch($productConcretePageSearchTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return bool
     */
    public function deleteProductConcretePageSearch(ProductConcretePageSearchTransfer $productConcretePageSearchTransfer): bool
    {
        return $this->entityManager->deleteProductConcretePageSearch($productConcretePageSearchTransfer);
    }
}
