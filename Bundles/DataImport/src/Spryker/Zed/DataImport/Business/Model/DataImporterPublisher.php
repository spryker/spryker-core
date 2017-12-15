<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model;

use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventInterface;

class DataImporterPublisher implements DataImporterPublisherInterface
{
    /**
     * @var DataImportToEventInterface
     */
    protected $eventFacade;

    /**
     * @var array
     */
    protected static $importedEntityEvents = [];

    /**
     * @param DataImportToEventInterface $eventFacade
     */
    public function __construct(DataImportToEventInterface $eventFacade)
    {
        $this->eventFacade = $eventFacade;
    }

    /**
     * @return array
     */
    public static function getImportedEntityEvents()
    {
        return self::$importedEntityEvents;
    }

    /**
     * @param array $importedEntityEvents
     */
    public static function setImportedEntityEvents(array $importedEntityEvents)
    {
        self::$importedEntityEvents = $importedEntityEvents;
    }

    /**
     * @param array $events
     *
     * @return void
     */
    public static function addImportedEntityEvents(array $events)
    {
        self::$importedEntityEvents = array_merge_recursive(static::$importedEntityEvents, $events);
    }


    /**
     * void
     */
    public function triggerEvents()
    {
        foreach (static::$importedEntityEvents as $event => $ids) {
            $uniqueIds = array_unique($ids);
            foreach ($uniqueIds as $id) {
                $this->eventFacade->trigger($event, (new EventEntityTransfer())->setId($id));
            }
        }
    }
}
