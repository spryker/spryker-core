<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Business;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;

interface SessionFacadeInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $sessionId
     *
     * @return void
     */
    public function removeYvesSessionLockFor($sessionId);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $sessionId
     *
     * @return void
     */
    public function removeZedSessionLockFor($sessionId);

    /**
     * Specification:
     * - Executes health check for the session service.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function executeSessionHealthCheck(): HealthCheckServiceResponseTransfer;
}
