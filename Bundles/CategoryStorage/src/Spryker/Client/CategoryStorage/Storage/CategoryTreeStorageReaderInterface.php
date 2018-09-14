<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Storage;

interface CategoryTreeStorageReaderInterface
{
    /**
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]|\ArrayObject
     */
    public function getCategories($locale);
}
