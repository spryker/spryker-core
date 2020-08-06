<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\Publisher;

interface DataImporterPublisherInterface
{
    /**
     * @param string $eventName
     * @param int $entityId
     *
     * @return void
     */
    public static function addEvent($eventName, $entityId): void;

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
