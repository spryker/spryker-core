<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\Publisher;

interface DataImporterPublisherInterface
{
    public const FLUSH_CHUNK_SIZE = 10000000;

    /**
     * @param string $eventName
     * @param int $entityId
     *
     * @return void
     */
    public static function addEvent($eventName, $entityId): void;

    /**
     * @deprecated use addEvent() instead.
     *
     * @param array $events
     *
     * @return void
     */
    public static function addImportedEntityEvents(array $events);

    /**
     * @param int $flushChunkSize
     *
     * @return void
     */
    public static function triggerEvents($flushChunkSize = self::FLUSH_CHUNK_SIZE);
}
