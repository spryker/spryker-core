<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Reader;

use Generated\Shared\Transfer\StoreTransfer;

/**
 * @deprecated Will be removed without replacement.
 */
interface StoreReferenceReaderInterface
{
    /**
     * @param string $storeReference
     *
     * @return string
     */
    public function getStoreNameByStoreReference(string $storeReference): string;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function extendStoreByStoreReference(StoreTransfer $storeTransfer): StoreTransfer;
}
