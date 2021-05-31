<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Business\Writer;

interface PublishAndSynchronizeHealthCheckStorageWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writePublishAndSynchronizeHealthCheckStorageCollectionByPublishAndSynchronizeHealthCheckEvents(array $eventTransfers): void;
}
