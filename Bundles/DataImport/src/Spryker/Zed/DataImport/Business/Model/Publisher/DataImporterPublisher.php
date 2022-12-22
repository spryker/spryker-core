<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\Publisher;

use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\DataImport\DataImportConstants;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Locator;

class DataImporterPublisher implements DataImporterPublisherInterface
{
    /**
     * @var int
     */
    public const DEFAULT_CHUNK_SIZE = 20000;

    /**
     * @var int
     */
    public const DEFAULT_FLUSH_CHUNK_SIZE = 10000000;

    /**
     * @var \Spryker\Zed\Event\Business\EventFacadeInterface|null
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
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|null $eventEntityTransfer
     *
     * @return void
     */
    public static function addEvent($eventName, $entityId, ?TransferInterface $eventEntityTransfer = null): void
    {
        if (isset(static::$triggeredEventIds[$eventName][$entityId])) {
            return;
        }

        static::$importedEntityEvents[$eventName][$entityId] = $eventEntityTransfer ?? true;

        $chunkSize = static::getChunkSize();

        if (count(static::$importedEntityEvents, COUNT_RECURSIVE) >= $chunkSize) {
            static::triggerEvents();
        }
    }

    /**
     * @deprecated Use {@link addEvent()} instead.
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
     * @param int|null $flushChunkSize
     *
     * @return void
     */
    public static function triggerEvents(?int $flushChunkSize = null): void
    {
        $uniqueEvents = static::$importedEntityEvents;
        foreach ($uniqueEvents as $eventName => $events) {
            $eventEntityTransfers = static::generateEventEntityTransfers($eventName, $events);
            static::loadEventFacade();
            static::$eventFacade->triggerBulk($eventName, $eventEntityTransfers);
        }

        static::$importedEntityEvents = [];

        if ($flushChunkSize === null) {
            $flushChunkSize = static::getFlushChunkSize();
        }

        if (count(static::$triggeredEventIds, COUNT_RECURSIVE) >= $flushChunkSize) {
            static::$triggeredEventIds = [];
        }
    }

    /**
     * @param string $eventName
     * @param array<int|\Generated\Shared\Transfer\EventEntityTransfer> $ids
     *
     * @return array<\Generated\Shared\Transfer\EventEntityTransfer>
     */
    protected static function generateEventEntityTransfers(string $eventName, array $ids): array
    {
        $eventEntityTransfers = [];
        foreach ($ids as $entityId => $event) {
            $eventEntityTransfers[] = static::getEventEntityTransfer((int)$entityId, $event);
            static::$triggeredEventIds[$eventName][$entityId] = true;
        }

        return $eventEntityTransfers;
    }

    /**
     * @param int $entityId
     * @param \Generated\Shared\Transfer\EventEntityTransfer|int|bool $event
     *
     * @return \Generated\Shared\Transfer\EventEntityTransfer
     */
    protected static function getEventEntityTransfer(int $entityId, EventEntityTransfer|int|bool $event): EventEntityTransfer
    {
            return $event instanceof EventEntityTransfer
                ? $event
                : (new EventEntityTransfer())->setId($entityId);
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
        if (static::$eventFacade === null) {
            $locatorClassName = Locator::class;
            /** @var \Generated\Zed\Ide\AutoCompletion&\Spryker\Shared\Kernel\LocatorLocatorInterface $locator */
            $locator = $locatorClassName::getInstance();
            static::$eventFacade = $locator->event()->facade();
        }
    }

    /**
     * Returns chunk size configured for triggering events
     *
     * @return int
     */
    protected static function getChunkSize()
    {
        return Config::get(DataImportConstants::PUBLISHER_TRIGGER_CHUNK_SIZE, static::DEFAULT_CHUNK_SIZE);
    }

    /**
     * Returns chunk size configured for flushing internal cache of triggered event ids
     *
     * @return int
     */
    protected static function getFlushChunkSize()
    {
        return Config::get(DataImportConstants::PUBLISHER_FLUSH_CHUNK_SIZE, static::DEFAULT_FLUSH_CHUNK_SIZE);
    }
}
