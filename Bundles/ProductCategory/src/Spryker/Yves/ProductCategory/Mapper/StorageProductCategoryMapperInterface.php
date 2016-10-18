<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Yves\ProductCategory\Mapper;

use Generated\Shared\Transfer\StorageProductTransfer;

interface StorageProductCategoryMapperInterface
{

    /**
     * @param \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     * @param array $persistedProduct
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function mapProductCategories(StorageProductTransfer $storageProductTransfer, array $persistedProduct);
}
