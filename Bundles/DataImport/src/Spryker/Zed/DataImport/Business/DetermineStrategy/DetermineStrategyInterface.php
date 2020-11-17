<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\DetermineStrategy;

interface DetermineStrategyInterface
{
    /**
     * Specification:
     * - Returns DataSetWriter that is applicable for current config, null otherwise
     *
     * @return mixed
     */
    public function getApplicable();
}
