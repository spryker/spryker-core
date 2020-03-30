<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Business;

/**
 * @method \Spryker\Zed\Kernel\Business\KernelBusinessFactory getFactory()
 */
interface KernelFacadeInterface
{
    /**
     * Specification:
     * - Builds a class map for the class resolver.
     *
     * @api
     *
     * @return void
     */
    public function buildResolvableClassCache(): void;
}
