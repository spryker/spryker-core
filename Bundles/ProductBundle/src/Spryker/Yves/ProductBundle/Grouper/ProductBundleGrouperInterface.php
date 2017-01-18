<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\ProductBundle\Grouper;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;

interface ProductBundleGrouperInterface
{

    /**
     * @param \ArrayObject|ItemTransfer[] $items
     * @param \ArrayObject|ItemTransfer[] $bundleItems
     *
     * @return array
     */
    public function getGroupedBundleItems(ArrayObject $items, ArrayObject $bundleItems);

}
