<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Business;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;

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

    /**
     * Specification:
     * - Generates sessionTrackingId using the UUID v4.
     * - Sets sessionTrackingId to sessionClient if empty.
     * - Sets `MessageAttributes.sessionTrackingId` if empty using generated sessionTrackingId.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    public function expandMessageAttributesWithSessionTrackingId(
        MessageAttributesTransfer $messageAttributesTransfer
    ): MessageAttributesTransfer;
}
