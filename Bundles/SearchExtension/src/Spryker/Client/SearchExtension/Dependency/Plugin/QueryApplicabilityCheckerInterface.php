<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

interface QueryApplicabilityCheckerInterface
{
    /**
     * Specification:
     * - Returns TRUE if {@link \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface} plugin is applicable and FALSE if not.
     *
     * @api
     *
     * @return bool
     */
    public function isApplicable(): bool;
}
