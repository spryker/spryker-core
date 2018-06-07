<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Communication\Plugin\SalesExtension;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesItemTransformerPluginInterface;

/**
 * @method \Spryker\Zed\SalesQuantity\Business\SalesQuantityFacadeInterface getFacade()
 */
class NonSplittableItemTransformerPlugin extends AbstractPlugin implements SalesItemTransformerPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isApplicable(ItemTransfer $itemTransfer): bool
    {
        return !$itemTransfer->getIsQuantitySplittable();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    public function transformItem(ItemTransfer $itemTransfer): ArrayObject
    {
        return $this->getFacade()->transformItem($itemTransfer);
    }
}
