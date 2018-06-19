<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Business;

interface ProductPackagingUnitGuiFacadeInterface
{
    /**
     * Specification:
     * - Retrieve infrastructural packaging unit type list as an array of strings.
     *
     * @api
     *
     * @return string[]
     */
    public function getInfrastructuralPackagingUnitTypeKeys(): array;
}
