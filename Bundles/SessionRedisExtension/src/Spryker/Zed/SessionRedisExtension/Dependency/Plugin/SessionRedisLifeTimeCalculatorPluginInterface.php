<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionRedisExtension\Dependency\Plugin;

use Generated\Shared\Transfer\HttpRequestTransfer;

/**
 * Use this plugin if life time of redis session must be changed.
 */
interface SessionRedisLifeTimeCalculatorPluginInterface
{
    /**
     * Specification:
     *  - Returns true if life time of redis session must be changed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HttpRequestTransfer $httpRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(HttpRequestTransfer $httpRequestTransfer): bool;

    /**
     * Specification:
     *  - Returns life time in seconds.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HttpRequestTransfer $httpRequestTransfer
     *
     * @return int
     */
    public function getLifeTime(HttpRequestTransfer $httpRequestTransfer): int;
}
