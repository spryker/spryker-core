<?php
/**
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace SprykerEngine\Shared\Lumberjack\Model;

use SprykerEngine\Shared\Lumberjack\Model\Collector\DataCollectorInterface;
use SprykerEngine\Shared\Lumberjack\Model\Writer\WriterInterface;

interface EventJournalInterface
{

    /**
     * @param DataCollectorInterface $dataCollector
     */
    public function addOrReplaceDataCollector(DataCollectorInterface $dataCollector);

    /**
     * @param EventInterface $event
     */
    public function applyCollectors(EventInterface $event);

    /**
     * @param EventInterface $event
     */
    public function saveEvent(EventInterface $event);

    /**
     * @param WriterInterface $writer
     */
    public function addOrReplaceEventWriter(WriterInterface $writer);

}
