<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business\Deleter;

interface ProductLabelDictionaryStorageDeleterInterface
{
    /**
     * @return void
     */
    public function deleteProductLabelDictionaryStorageCollection(): void;
}
