<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Business\Writer;

interface PublishAndSynchronizeHealthCheckSearchWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writePublishAndSynchronizeHealthCheckSearchCollectionByPublishAndSynchronizeHealthCheckEvents(array $eventTransfers): void;
}
