<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log\Business;

interface LogFacadeInterface
{
    /**
     * Specification:
     * - Clear all logs.
     *
     * @api
     *
     * @return void
     */
    public function clearLogs();

    /**
     * Specification:
     * - Executes all LogListenerInterfaces to start services like filebeat.
     *
     * @api
     *
     * @return void
     */
    public function startListener();

    /**
     * Specification:
     * - Executes all LogListenerInterfaces to stop services like filebeat.
     *
     * @api
     *
     * @return void
     */
    public function stopListener();
}
