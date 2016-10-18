<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */
namespace Spryker\Yves\ProductImage\Mapper;

use Generated\Shared\Transfer\StorageProductTransfer;

interface StorageImageMapperInterface
{

    /**
     * @param StorageProductTransfer $storageProductTransfer
     *
     * @return StorageProductTransfer $storageProductTransfer
     */
    public function mapProductImages(StorageProductTransfer $storageProductTransfer);

}
