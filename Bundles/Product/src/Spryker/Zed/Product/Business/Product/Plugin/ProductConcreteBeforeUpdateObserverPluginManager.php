<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Product\Observer\ProductConcreteUpdateObserverInterface;

class ProductConcreteBeforeUpdateObserverPluginManager implements ProductConcreteUpdateObserverInterface
{
    /**
     * @var \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface[]
     */
    protected $beforeUpdateCollection;

    /**
     * @param \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface[] $beforeUpdateCollection
     */
    public function __construct(array $beforeUpdateCollection)
    {
        $this->beforeUpdateCollection = $beforeUpdateCollection;
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
        foreach ($this->beforeUpdateCollection as $productConcretePluginUpdate) {
            $productConcreteTransfer = $productConcretePluginUpdate->update($productConcreteTransfer);
        }

        return $productConcreteTransfer;
    }
}
