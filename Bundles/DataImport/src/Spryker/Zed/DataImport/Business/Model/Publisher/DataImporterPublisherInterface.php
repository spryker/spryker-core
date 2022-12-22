<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\Publisher;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface DataImporterPublisherInterface
{
    /**
     * @param string $eventName
     * @param int $entityId
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|null $eventEntityTransfer
     *
     * @return void
     */
    public static function addEvent($eventName, $entityId, ?TransferInterface $eventEntityTransfer = null): void;

    /**
     * @deprecated Use {@link addEvent()} instead.
     *
     * @param array $events
     *
     * @return void
     */
    public static function addImportedEntityEvents(array $events);

    /**
     * @param int|null $flushChunkSize
     *
     * @return void
     */
    public static function triggerEvents(?int $flushChunkSize = null): void;
}
