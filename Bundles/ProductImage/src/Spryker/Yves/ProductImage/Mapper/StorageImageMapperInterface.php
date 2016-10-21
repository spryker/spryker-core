<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductImage\Mapper;

use Generated\Shared\Transfer\StorageProductTransfer;

interface StorageImageMapperInterface
{

    /**
     * @param \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     */
    public function mapProductImages(StorageProductTransfer $storageProductTransfer);

}
