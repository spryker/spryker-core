<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\KeyGenerator;

interface ProductListKeyGeneratorInterface
{
    /**
     * @param string $name
     *
     * @return string
     */
    public function generateProductListKey(string $name): string;
}
