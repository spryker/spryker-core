<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Product\Observer\ProductConcreteAddItemObserverInterface;

class ProductConcreteAddItemObserverPluginManager implements ProductConcreteAddItemObserverInterface
{
    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginReadInterface[]
     */
    protected $addItemCollection;

    /**
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginReadInterface[] $addItemCollection
     */
    public function __construct(array $addItemCollection)
    {
        $this->addItemCollection = $addItemCollection;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function addItem(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        foreach ($this->addItemCollection as $productConcretePluginRead) {
            $productConcreteTransfer = $productConcretePluginRead->read($productConcreteTransfer);
        }

        return $productConcreteTransfer;
    }
}
