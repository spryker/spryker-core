<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\ProductImage\Builder;

use Generated\Shared\Transfer\StorageProductTransfer;

interface StorageImageBuilderInterface
{

    /**
     * @param StorageProductTransfer $storageProductTransfer
     *
     * @return StorageProductTransfer $storageProductTransfer
     */
    public function setSelectedProductDisplayImages(StorageProductTransfer $storageProductTransfer);

}
