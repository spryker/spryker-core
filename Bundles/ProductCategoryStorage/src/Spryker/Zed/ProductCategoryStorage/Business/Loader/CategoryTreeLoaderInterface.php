<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business\Loader;

interface CategoryTreeLoaderInterface
{
    /**
     * @return array
     */
    public function loadCategoryTree(): array;
}
