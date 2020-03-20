<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilGlob;

interface UtilGlobServiceInterface
{
    /**
     * Specification:
     * - Find pathnames matching a pattern.
     * - PHP's `glob` doesn't work with stream wrapper this method works with stream wrapper.
     * - Falls back to PHP's `glob` when no stream wrapper is used.
     *
     * @api
     *
     * @param string $pattern
     * @param int $flags
     *
     * @return array
     */
    public function glob(string $pattern, int $flags = 0): array;
}
