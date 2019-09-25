<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Product\Observer\ProductConcreteUpdateObserverInterface;

class ProductConcreteAfterUpdateObserverPluginManager implements ProductConcreteUpdateObserverInterface
{
    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface[]
     */
    protected $afterUpdateCollection;

    /**
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface[] $afterUpdateCollection
     */
    public function __construct(array $afterUpdateCollection)
    {
        $this->afterUpdateCollection = $afterUpdateCollection;
    }

    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function update(ProductConcreteTransfer $productConcreteTransfer)
    {
        foreach ($this->afterUpdateCollection as $productConcretePluginUpdate) {
            $productConcreteTransfer = $productConcretePluginUpdate->update($productConcreteTransfer);
        }

        return $productConcreteTransfer;
    }
}
