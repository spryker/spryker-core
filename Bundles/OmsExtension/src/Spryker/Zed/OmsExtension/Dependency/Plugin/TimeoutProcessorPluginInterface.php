<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OmsEventTransfer;
use Generated\Shared\Transfer\TimeoutProcessorTimeoutRequestTransfer;
use Generated\Shared\Transfer\TimeoutProcessorTimeoutResponseTransfer;

/**
 * Allow to define their own timeout processor
 */
interface TimeoutProcessorPluginInterface
{
    /**
     * Specification:
     * -
     *
     * @api
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Specification:
     * -
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
     * -
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TimeoutProcessorTimeoutRequestTransfer $timeoutProcessorTimeoutRequestTransfer
     *
     * @return \Generated\Shared\Transfer\TimeoutProcessorTimeoutResponseTransfer
     */
    public function calculateTimeout(TimeoutProcessorTimeoutRequestTransfer $timeoutProcessorTimeoutRequestTransfer): TimeoutProcessorTimeoutResponseTransfer;
}
