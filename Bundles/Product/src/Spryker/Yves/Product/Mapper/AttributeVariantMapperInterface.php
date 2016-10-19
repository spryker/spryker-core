<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Product\Mapper;

use Generated\Shared\Transfer\StorageProductTransfer;

interface AttributeVariantMapperInterface
{

    /**
     * @param \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function setSuperAttributes(StorageProductTransfer $storageProductTransfer);

    /**
     * @param array $selectedAttributes
     * @param \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function setSelectedVariants(array $selectedAttributes, StorageProductTransfer $storageProductTransfer);

}
