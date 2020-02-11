<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Product\Business\Product\Observer\ProductAbstractUpdateObserverInterface;

class ProductAbstractBeforeUpdateObserverPluginManager implements ProductAbstractUpdateObserverInterface
{
    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface[]
     */
    protected $beforeUpdateCollection;

    /**
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface[] $beforeUpdateCollection
     */
    public function __construct(array $beforeUpdateCollection)
    {
        $this->beforeUpdateCollection = $beforeUpdateCollection;
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
        foreach ($this->beforeUpdateCollection as $productAbstractPluginUpdate) {
            $productAbstractTransfer = $productAbstractPluginUpdate->update($productAbstractTransfer);
        }

        return $productAbstractTransfer;
    }
}
