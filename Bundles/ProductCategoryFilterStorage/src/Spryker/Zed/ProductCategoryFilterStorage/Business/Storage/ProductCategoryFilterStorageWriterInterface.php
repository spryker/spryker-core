<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterStorage\Business\Storage;

interface ProductCategoryFilterStorageWriterInterface
{
    /**
     * @param array $categoryIds
     *
     * @return void
     */
    public function publish(array $categoryIds);

    /**
     * @param array $categoryIds
     *
     * @return void
     */
    public function unpublish(array $categoryIds);
}
