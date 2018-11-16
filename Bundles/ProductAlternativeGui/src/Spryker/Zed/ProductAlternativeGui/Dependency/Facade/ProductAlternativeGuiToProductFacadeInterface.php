<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Dependency\Facade;

interface ProductAlternativeGuiToProductFacadeInterface
{
    /**
     * @param string $suggestion
     *
     * @return string[]
     */
    public function suggestProductAbstract(string $suggestion): array;

    /**
     * @param string $suggestion
     *
     * @return string[]
     */
    public function suggestProductConcrete(string $suggestion): array;
}
