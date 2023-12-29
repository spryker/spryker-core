<?php

namespace Spryker\Zed\OmsExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OmsEventTriggeredTransfer;

interface OmsEventTriggeredListenerPluginInterface
{
    /**
     * Specification:
     * - Executed after OMS event has been triggered.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsEventTriggeredTransfer $omsEventTriggeredTransfer
     *
     * @return bool
     */
    public function isApplicable(OmsEventTriggeredTransfer $omsEventTriggeredTransfer): bool;

    /**
     * Specification:
     * - Executed after OMS event has been triggered.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsEventTriggeredTransfer $omsEventTriggeredTransfer
     *
     * @return void
     */
    public function onEventTriggered(OmsEventTriggeredTransfer $omsEventTriggeredTransfer): void;
}
