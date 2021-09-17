<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Storage;

use ArrayObject;

interface CategoryTreeStorageReaderInterface
{
    /**
     * @param string $localeName
     * @param string $storeName
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeStorageTransfer>
     */
    public function getCategories(string $localeName, string $storeName): ArrayObject;
}
