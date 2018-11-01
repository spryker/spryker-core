<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\Publisher;

use Generated\Shared\Transfer\EventEntityTransfer;

class DataImporterPublisher implements DataImporterPublisherInterface
{
    public const CHUNK_SIZE = 20000;

    /**
     * @var \Spryker\Zed\Event\Business\EventFacadeInterface
     */
    protected static $eventFacade;

    /**
     * @var array
     */
    protected static $importedEntityEvents = [];

    /**
     * @var array
     */
    protected static $triggeredEventIds = [];

    /**
     * @param string $eventName
     * @param int $entityId
     *
     * @return void
     */
    public static function addEvent($eventName, $entityId): void
    {
        if (isset(static::$triggeredEventIds[$eventName][$entityId])) {
            return;
        }

        static::$importedEntityEvents[$eventName][$entityId] = true;

        if (count(static::$importedEntityEvents, COUNT_RECURSIVE) >= static::CHUNK_SIZE) {
            static::triggerEvents();
        }
    }

    /**
     * @deprecated use addEvent() instead.
     *
     * @param array $events
     *
     * @return void
     */
    public static function addImportedEntityEvents(array $events): void
    {
        static::$importedEntityEvents = array_merge_recursive(static::$importedEntityEvents, $events);
    }

    /**
     * @param int $flushChunkSize
     *
     * @return void
     */
    public static function triggerEvents($flushChunkSize = self::FLUSH_CHUNK_SIZE): void
    {
        $uniqueEvents = static::$importedEntityEvents;
        foreach ($uniqueEvents as $eventName => $ids) {
            $events = [];
            foreach ($ids as $key => $value) {
                $events[] = (new EventEntityTransfer())->setId($key);
                static::$triggeredEventIds[$eventName][$key] = true;
            }

            static::loadEventFacade();
            static::$eventFacade->triggerBulk($eventName, $events);
        }

        static::$importedEntityEvents = [];

        if (count(static::$triggeredEventIds, COUNT_RECURSIVE) > $flushChunkSize) {
            static::$triggeredEventIds = [];
        }
    }

    /**
     * @deprecated $ids will be unique by calling DataImporterPublisher::addEvent(),
     * No necessary to call this method anymore
     *
     * @param array $mergedArray
     *
     * @return array
     */
    protected static function getUniqueArray(array $mergedArray): array
    {
        $uniqueArray = [];
        foreach ($mergedArray as $event => $ids) {
            $uniqueArray[$event] = array_unique($ids, SORT_REGULAR);
        }

        return $uniqueArray;
    }

    /**
     * Added here for keeping the BC, needs to inject this from outside
     *
     * @return void
     */
    protected static function loadEventFacade()
    {
        if (!static::$eventFacade) {
            $locatorClassName = '\Spryker\Zed\Kernel\Locator';
            static::$eventFacade = $locatorClassName::getInstance()->event()->facade();
        }
    }
}
