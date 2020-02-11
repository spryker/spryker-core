<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Product\Business\Product\Observer\ProductAbstractUpdateObserverInterface;

class ProductAbstractAfterUpdateObserverPluginManager implements ProductAbstractUpdateObserverInterface
{
    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface[]
     */
    protected $afterUpdateCollection;

    /**
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface[] $afterUpdateCollection
     */
    public function __construct(array $afterUpdateCollection)
    {
        $this->afterUpdateCollection = $afterUpdateCollection;
    }

    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function update(ProductAbstractTransfer $productAbstractTransfer)
    {
        foreach ($this->afterUpdateCollection as $productAbstractPluginUpdate) {
            $productAbstractTransfer = $productAbstractPluginUpdate->update($productAbstractTransfer);
        }

        return $productAbstractTransfer;
    }
}
