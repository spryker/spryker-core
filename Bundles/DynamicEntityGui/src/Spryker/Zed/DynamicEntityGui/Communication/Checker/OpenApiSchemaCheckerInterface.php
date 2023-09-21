<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Communication\Checker;

interface OpenApiSchemaCheckerInterface
{
    /**
     * @return bool
     */
    public function isSchemaFileActual(): bool;
}
