<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OmsEventTransfer;
use Generated\Shared\Transfer\TimeoutProcessorTimeoutRequestTransfer;
use Generated\Shared\Transfer\TimeoutProcessorTimeoutResponseTransfer;

/**
 * Allows to define timeout processor for state machine event to calculate event timeout.
 */
interface TimeoutProcessorPluginInterface
{
    /**
     * Specification:
     * - Returns name of TimeoutProcessor.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Specification:
     * - Returns label for TimeoutProcessor that will be displayed on state machine visualisation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsEventTransfer $omsEventTransfer
     *
     * @return string
     */
    public function getLabel(OmsEventTransfer $omsEventTransfer): string;

    /**
     * Specification:
     * - Calculates timeout based on event and order item.
     * - Return `TimeoutProcessorTimeoutResponseTransfer` with timestamp when event should be triggered.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TimeoutProcessorTimeoutRequestTransfer $timeoutProcessorTimeoutRequestTransfer
     *
     * @return \Generated\Shared\Transfer\TimeoutProcessorTimeoutResponseTransfer
     */
    public function calculateTimeout(TimeoutProcessorTimeoutRequestTransfer $timeoutProcessorTimeoutRequestTransfer): TimeoutProcessorTimeoutResponseTransfer;
}
