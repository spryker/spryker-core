<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Storage;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;

interface CategoryTreeStorageReaderInterface
{

    /**
     * @param string $locale
     *
     * @return CategoryNodeStorageTransfer[]
     */
    public function getCategories($locale);
}
