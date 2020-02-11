<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Product\Observer\ProductConcreteCreateObserverInterface;

class ProductConcreteAfterCreateObserverPluginManager implements ProductConcreteCreateObserverInterface
{
    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginCreateInterface[]
     */
    protected $afterCreateCollection;

    /**
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginCreateInterface[] $afterCreateCollection
     */
    public function __construct(array $afterCreateCollection)
    {
        $this->afterCreateCollection = $afterCreateCollection;
    }

    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function create(ProductConcreteTransfer $productConcreteTransfer)
    {
        foreach ($this->afterCreateCollection as $productConcretePluginCreate) {
            $productConcreteTransfer = $productConcretePluginCreate->create($productConcreteTransfer);
        }

        return $productConcreteTransfer;
    }
}
