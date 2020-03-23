<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence;

interface ProductLabelStorageRepositoryInterface
{
    /**
     * @return \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer[]
     */
    public function getProductLabelLocalizedAttributes(): array;

    /**
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer[]
     */
    public function getProductLabelDictionaryStorageTransfers(): array;
}
