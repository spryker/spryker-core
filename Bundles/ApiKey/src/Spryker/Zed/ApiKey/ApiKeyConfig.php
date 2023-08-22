<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ApiKeyConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const HASH_ALGO = 'sha256';

    /**
     * Specification:
     * - Returns an algorithm for hashing API key.
     *
     * @api
     *
     * @return string
     */
    public function getHashAlgorithm(): string
    {
        return static::HASH_ALGO;
    }
}
