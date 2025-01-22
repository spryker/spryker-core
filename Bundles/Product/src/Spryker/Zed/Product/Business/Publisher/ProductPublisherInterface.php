<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Publisher;

use Generated\Shared\Transfer\MessageSendingContextTransfer;
use Generated\Shared\Transfer\ProductPublisherConfigTransfer;

interface ProductPublisherInterface
{
    /**
     * @param \Generated\Shared\Transfer\MessageSendingContextTransfer $messageSendingContextTransfer
     *
     * @return bool
     */
    public function canPublishMessage(MessageSendingContextTransfer $messageSendingContextTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\ProductPublisherConfigTransfer $productPublisherConfigTransfer
     *
     * @return void
     */
    public function publish(ProductPublisherConfigTransfer $productPublisherConfigTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ProductPublisherConfigTransfer $productPublisherConfigTransfer
     *
     * @return void
     */
    public function unpublish(ProductPublisherConfigTransfer $productPublisherConfigTransfer): void;

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function publishByProductAbstractEvents(array $eventEntityTransfers): void;

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function publishByProductEvents(array $eventEntityTransfers): void;
}
